<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers/ActivityLogger.php';

class LoginController {
    
    public function index() {
        if (isLoggedIn()) {
            header('Location: /');
            exit;
        }

        $error = '';
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->handleLogin();
            $error = $result['error'];
            $username = $result['username'];
        }

        $csrf_token = generateCSRFToken();
        require __DIR__ . '/../views/login.view.php';
    }

    private function handleLogin() {
        $error = '';
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['error' => 'Ошибка безопасности (CSRF)', 'username' => $username];
        }

        if (empty($username) || empty($password)) {
            return ['error' => 'Заполните все поля', 'username' => $username];
        }

        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT id, username, password_hash, failed_attempts, locked_until FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                    return ['error' => 'Аккаунт временно заблокирован. Попробуйте позже.', 'username' => $username];
                }
                
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    $stmt = $db->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    
                    // Логируем успешный вход
                    logActivity(ActivityActions::USER_LOGIN, "Пользователь {$user['username']} вошел в систему", 'user', $user['id']);
                    
                    session_regenerate_id(true);
                    header('Location: /');
                    exit;
                } else {
                    $failed_attempts = $user['failed_attempts'] + 1;
                    $locked_until = null;
                    
                    if ($failed_attempts >= MAX_LOGIN_ATTEMPTS) {
                        $locked_until = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
                        $error = 'Слишком много неудачных попыток. Аккаунт заблокирован на 15 минут.';
                    } else {
                        $error = 'Неверное имя пользователя или пароль';
                    }
                    
                    $stmt = $db->prepare("UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?");
                    $stmt->execute([$failed_attempts, $locked_until, $user['id']]);
                }
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
        } catch (PDOException $e) {
            $error = 'Ошибка базы данных. Обратитесь к администратору.';
        }

        return ['error' => $error, 'username' => $username];
    }
}
