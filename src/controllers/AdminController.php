<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../email.php';

class AdminController {
    
    public function index() {
        // Устанавливаем кодировку UTF-8 для правильного отображения
        header('Content-Type: text/html; charset=utf-8');
        
        if (!isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $db = getDB();
        $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || $user['is_admin'] != 1) {
            $this->showAccessDenied();
            exit;
        }

        // Обработка POST запросов с редиректом (Post-Redirect-Get паттерн)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['send_email'])) {
                $result = $this->handleSendEmail();
                if ($result['success']) {
                    header('Location: /admin?email_sent=1');
                } else {
                    header('Location: /admin?email_error=' . urlencode($result['error']));
                }
                exit;
            } elseif (isset($_POST['send_newsletter'])) {
                $result = $this->handleSendNewsletter();
                if ($result['success']) {
                    header('Location: /admin?newsletter_sent=1&message=' . urlencode($result['error']));
                } else {
                    header('Location: /admin?newsletter_error=' . urlencode($result['error']));
                }
                exit;
            }
        }

        // Получаем сообщения из GET параметров
        $emailSent = isset($_GET['email_sent']) || isset($_GET['newsletter_sent']);
        $emailError = '';
        
        if (isset($_GET['email_sent'])) {
            $emailError = 'Письмо успешно отправлено!';
        } elseif (isset($_GET['newsletter_sent'])) {
            $emailError = $_GET['message'] ?? 'Рассылка завершена!';
        } elseif (isset($_GET['email_error'])) {
            $emailError = $_GET['email_error'];
        } elseif (isset($_GET['newsletter_error'])) {
            $emailError = $_GET['newsletter_error'];
        }

        try {
            // Устанавливаем кодировку для текущего соединения
            $db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            $users = $db->query("SELECT id, username, email, is_admin, created_at, last_login, failed_attempts, locked_until FROM users ORDER BY id DESC")->fetchAll();
            $tokens = $db->query("SELECT pr.id, pr.user_id, u.username, u.email, LEFT(pr.token, 20) as token_preview, pr.created_at, pr.expires_at, pr.used FROM password_resets pr JOIN users u ON pr.user_id = u.id ORDER BY pr.created_at DESC LIMIT 10")->fetchAll();
            
            // Загружаем только шаблоны подходящие для массовой рассылки
            $templates = $db->query("SELECT id, name, subject, body, description FROM email_templates WHERE name IN ('newsletter', 'announcement', 'promo') ORDER BY name")->fetchAll();
            
            $stats = $db->query("SELECT 
                (SELECT COUNT(*) FROM users) as total_users,
                (SELECT COUNT(*) FROM password_resets WHERE used = FALSE AND expires_at > NOW()) as active_tokens,
                (SELECT COUNT(*) FROM password_resets WHERE used = TRUE) as used_tokens
            ")->fetch();
        } catch (PDOException $e) {
            die("Ошибка БД: " . $e->getMessage());
        }

        $csrf_token = generateCSRFToken();
        $disableLoader = true; // Отключаем лоадер
        require __DIR__ . '/../views/admin.view.php';
    }



    private function handleSendEmail() {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['success' => false, 'error' => 'Ошибка безопасности'];
        }

        $to = $_POST['email_to'] ?? '';
        $subject = $_POST['email_subject'] ?? '';
        $body = $_POST['email_body'] ?? '';
        
        if (empty($to) || empty($subject) || empty($body)) {
            return ['success' => false, 'error' => 'Заполните все поля'];
        }

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Неверный формат email'];
        }

        // Используем красивый шаблон для рассылки
        $result = sendNewsletterEmail($to, $subject, $body);
        
        if ($result) {
            return ['success' => true, 'error' => ''];
        } else {
            return ['success' => false, 'error' => 'Ошибка отправки. Проверьте логи.'];
        }
    }

    private function handleSendNewsletter() {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['success' => false, 'error' => 'Ошибка безопасности'];
        }

        $subject = $_POST['newsletter_subject'] ?? '';
        $message = $_POST['newsletter_message'] ?? '';
        $templateId = (int)($_POST['template_id'] ?? 0);
        
        if (empty($subject) || empty($message)) {
            return ['success' => false, 'error' => 'Заполните все поля'];
        }

        try {
            $db = getDB();
            
            // Если выбран шаблон, загружаем его
            $templateBody = null;
            if ($templateId > 0) {
                $stmt = $db->prepare("SELECT body FROM email_templates WHERE id = ?");
                $stmt->execute([$templateId]);
                $template = $stmt->fetch();
                if ($template) {
                    $templateBody = $template['body'];
                }
            }
            
            $stmt = $db->query("SELECT email, username FROM users");
            $users = $stmt->fetchAll();
            
            if (empty($users)) {
                return ['success' => false, 'error' => 'Нет пользователей для рассылки'];
            }
            
            $sent = 0;
            $failed = 0;
            
            foreach ($users as $user) {
                // Если есть шаблон, используем его
                if ($templateBody) {
                    $result = $this->sendTemplatedEmail($user['email'], $user['username'], $subject, $message, $templateBody);
                } else {
                    // Иначе используем обычную рассылку
                    $result = sendNewsletterEmail($user['email'], $subject, $message);
                }
                
                if ($result) {
                    $sent++;
                } else {
                    $failed++;
                }
                
                // Небольшая задержка между отправками
                usleep(100000); // 0.1 секунды
            }
            
            $message = "Отправлено: {$sent}, Ошибок: {$failed}";
            return ['success' => true, 'error' => $message];
            
        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Ошибка БД'];
        }
    }

    private function sendTemplatedEmail($email, $username, $subject, $message, $templateBody) {
        // Заменяем переменные в шаблоне
        $html = str_replace('{{username}}', htmlspecialchars($username), $templateBody);
        $html = str_replace('{{message}}', nl2br(htmlspecialchars($message)), $html);
        $html = str_replace('{{subject}}', htmlspecialchars($subject), $html);
        
        // Отправляем через базовую функцию
        return sendEmail($email, $subject, $html);
    }

    private function showAccessDenied() {
        $user_id = $_SESSION['user_id'];
        $db = getDB();
        $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        $is_admin = $user['is_admin'] ?? 0;
        
        require __DIR__ . '/../views/access_denied.view.php';
    }
}
