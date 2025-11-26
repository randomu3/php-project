<?php

namespace AuraUI\Controllers;

use AuraUI\Helpers\ActivityActions;

use function logActivity;

use PDOException;

/**
 *  Login Controller
 *
 * @package AuraUI\Controllers
 */
class LoginController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
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

    /**
     * Handle Login
     *
     * @return array Data array
     */
    private function handleLogin(): array
    {
        $error = '';
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->logLoginAttempt($ip, $username, $userAgent, false, 'CSRF error');
            return ['error' => 'Ошибка безопасности (CSRF)', 'username' => $username];
        }

        // Проверяем, не заблокирован ли IP
        if ($this->isIPBlocked($ip)) {
            return ['error' => 'Ваш IP заблокирован. Обратитесь к администратору.', 'username' => $username];
        }

        if (empty($username) || empty($password)) {
            return ['error' => 'Заполните все поля', 'username' => $username];
        }

        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT id, username, email, password_hash, failed_attempts, locked_until, email_verified FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user) {
                if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
                    $this->logLoginAttempt($ip, $username, $userAgent, false, 'Account locked');
                    return ['error' => 'Аккаунт временно заблокирован. Попробуйте позже.', 'username' => $username];
                }

                // Проверяем подтверждение email
                if (!$user['email_verified']) {
                    $this->logLoginAttempt($ip, $username, $userAgent, false, 'Email not verified');
                    return ['error' => 'Email не подтверждён. Проверьте почту и перейдите по ссылке подтверждения.', 'username' => $username];
                }

                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    $stmt = $db->prepare("UPDATE users SET failed_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    // Логируем успешный вход
                    $this->logLoginAttempt($ip, $username, $userAgent, true, null);
                    logActivity(ActivityActions::USER_LOGIN, sprintf('Пользователь %s вошел в систему', $user['username']), 'user', $user['id']);
                    
                    // Сохраняем сессию в БД
                    $this->saveSession($user['id'], $ip, $userAgent, $rememberMe);

                    // Если "Запомнить меня" - устанавливаем долгую сессию
                    if ($rememberMe) {
                        $this->setRememberMeCookie($user['id']);
                    }

                    session_regenerate_id(true);
                    header('Location: /');
                    exit;
                }

                $failed_attempts = $user['failed_attempts'] + 1;
                $locked_until = null;
                if ($failed_attempts >= MAX_LOGIN_ATTEMPTS) {
                    $locked_until = date('Y-m-d H:i:s', time() + LOCKOUT_TIME);
                    $error = 'Слишком много неудачных попыток. Аккаунт заблокирован на 15 минут.';
                    
                    // Уведомляем админов о блокировке аккаунта
                    $notifier = new \AuraUI\Helpers\AdminNotifier();
                    $notifier->notifyAccountLocked($user['id'], $user['username'], LOCKOUT_TIME / 60);
                } else {
                    $error = 'Неверное имя пользователя или пароль';
                }
                
                // Уведомляем о подозрительной активности при 3+ попытках
                if ($failed_attempts >= 3) {
                    $notifier = new \AuraUI\Helpers\AdminNotifier();
                    $notifier->notifySuspiciousActivity($ip, $failed_attempts, $username);
                }

                $stmt = $db->prepare("UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?");
                $stmt->execute([$failed_attempts, $locked_until, $user['id']]);
                
                $this->logLoginAttempt($ip, $username, $userAgent, false, 'Wrong password');
            } else {
                $this->logLoginAttempt($ip, $username, $userAgent, false, 'User not found');
                $error = 'Неверное имя пользователя или пароль';
            }
        } catch (PDOException) {
            $error = 'Ошибка базы данных. Обратитесь к администратору.';
        }

        return ['error' => $error, 'username' => $username];
    }

    /**
     * Log login attempt to database
     *
     * @param string $ip IP address
     * @param string $username Username attempted
     * @param string $userAgent User agent string
     * @param bool $success Whether login was successful
     * @param string|null $reason Failure reason
     *
     * @return void
     */
    private function logLoginAttempt(string $ip, string $username, string $userAgent, bool $success, ?string $reason): void
    {
        try {
            $db = getDB();
            $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, username, user_agent, success, failure_reason) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$ip, $username, $userAgent, $success ? 1 : 0, $reason]);
        } catch (PDOException $e) {
            error_log("Failed to log login attempt: " . $e->getMessage());
        }
    }

    /**
     * Check if IP is blocked
     *
     * @param string $ip IP address to check
     *
     * @return bool True if blocked
     */
    private function isIPBlocked(string $ip): bool
    {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT id FROM blocked_ips WHERE ip_address = ? AND (is_permanent = 1 OR expires_at > NOW())");
            $stmt->execute([$ip]);
            return (bool)$stmt->fetch();
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Save user session to database
     *
     * @param int $userId User ID
     * @param string $ip IP address
     * @param string $userAgent User agent string
     * @param bool $rememberMe Whether to remember the session
     *
     * @return void
     */
    private function saveSession(int $userId, string $ip, string $userAgent, bool $rememberMe = false): void
    {
        try {
            $db = getDB();
            $sessionId = session_id();
            $deviceInfo = $this->parseUserAgent($userAgent);
            
            // Удаляем старую сессию с таким же ID если есть
            $stmt = $db->prepare("DELETE FROM user_sessions WHERE session_id = ?");
            $stmt->execute([$sessionId]);
            
            // Создаём новую запись
            $stmt = $db->prepare("INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, device_info, remember_me) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $sessionId, $ip, $userAgent, $deviceInfo, $rememberMe ? 1 : 0]);
        } catch (PDOException $e) {
            error_log("Failed to save session: " . $e->getMessage());
        }
    }

    /**
     * Set remember me cookie for persistent login
     *
     * @param int $userId User ID
     *
     * @return void
     */
    private function setRememberMeCookie(int $userId): void
    {
        try {
            $token = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $token);
            $expiresAt = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60); // 30 дней
            
            $db = getDB();
            
            // Удаляем старые токены пользователя
            $stmt = $db->prepare("DELETE FROM remember_tokens WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Сохраняем новый токен
            $stmt = $db->prepare("INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $hashedToken, $expiresAt]);
            
            // Устанавливаем cookie
            setcookie('remember_token', $token, [
                'expires' => time() + 30 * 24 * 60 * 60,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        } catch (PDOException $e) {
            error_log("Failed to set remember me cookie: " . $e->getMessage());
        }
    }

    /**
     * Parse user agent to get device info
     *
     * @param string $userAgent User agent string
     *
     * @return string Device info
     */
    private function parseUserAgent(string $userAgent): string
    {
        $device = 'Unknown';
        
        if (preg_match('/Windows/i', $userAgent)) $device = 'Windows';
        elseif (preg_match('/Macintosh|Mac OS/i', $userAgent)) $device = 'Mac';
        elseif (preg_match('/Linux/i', $userAgent)) $device = 'Linux';
        elseif (preg_match('/iPhone/i', $userAgent)) $device = 'iPhone';
        elseif (preg_match('/iPad/i', $userAgent)) $device = 'iPad';
        elseif (preg_match('/Android/i', $userAgent)) $device = 'Android';
        
        $browser = 'Unknown';
        if (preg_match('/Chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/Firefox/i', $userAgent)) $browser = 'Firefox';
        elseif (preg_match('/Safari/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/Edge/i', $userAgent)) $browser = 'Edge';
        elseif (preg_match('/Opera|OPR/i', $userAgent)) $browser = 'Opera';
        
        return "{$device} / {$browser}";
    }
}
