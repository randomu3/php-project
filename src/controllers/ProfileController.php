<?php

namespace AuraUI\Controllers;

use AuraUI\Helpers\ActivityActions;
use AuraUI\Helpers\ImageUploader;
use AuraUI\Helpers\NotificationIcons;
use AuraUI\Helpers\NotificationTypes;
use Exception;
use PDOException;

use function logActivity;
use function notify;
use function sendEmailVerification;

/**
 *  Profile Controller
 *
 * @package AuraUI\Controllers
 */
class ProfileController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        requireLogin();

        // Получаем сообщения из сессии (после редиректа)
        $success = $_SESSION['profile_success'] ?? '';
        $error = $_SESSION['profile_error'] ?? '';
        $activeTab = $_SESSION['profile_tab'] ?? 'info';

        // Очищаем сообщения из сессии
        unset($_SESSION['profile_success'], $_SESSION['profile_error'], $_SESSION['profile_tab']);

        $user = $this->getCurrentUser();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'update_profile') {
                $result = $this->handleUpdateProfile();
                $_SESSION['profile_success'] = $result['success'];
                $_SESSION['profile_error'] = $result['error'];
                $_SESSION['profile_tab'] = 'edit';
                header('Location: /profile');
                exit;
            }

            if ($action === 'change_password') {
                $result = $this->handleChangePassword();
                $_SESSION['profile_success'] = $result['success'];
                $_SESSION['profile_error'] = $result['error'];
                $_SESSION['profile_tab'] = 'password';
                header('Location: /profile');
                exit;
            }

            if ($action === 'upload_avatar') {
                $result = $this->handleUploadAvatar();
                $_SESSION['profile_success'] = $result['success'];
                $_SESSION['profile_error'] = $result['error'];
                $_SESSION['profile_tab'] = 'info';
                header('Location: /profile');
                exit;
            }

            if ($action === 'delete_avatar') {
                $result = $this->handleDeleteAvatar();
                $_SESSION['profile_success'] = $result['success'];
                $_SESSION['profile_error'] = $result['error'];
                $_SESSION['profile_tab'] = 'info';
                header('Location: /profile');
                exit;
            }
        }

        $csrf_token = generateCSRFToken();
        $pageTitle = 'Профиль | AuraUI';
        $disableLoader = true; // Временно отключаем лоадер для отладки

        // Дополнительные стили для страницы профиля
        $additionalCSS = '
            /* Glassmorphism Styles */
            .glass-panel {
                background: rgba(30, 41, 59, 0.4);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 1.5rem;
            }
            
            .glass-input {
                background: rgba(15, 23, 42, 0.6);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: white;
                border-radius: 0.75rem;
                padding: 0.75rem 1rem;
                transition: all 0.2s;
            }
            .glass-input:focus {
                border-color: #a855f7;
                outline: none;
                box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.2);
                background: rgba(15, 23, 42, 0.8);
            }
            
            .tab-btn {
                padding: 0.75rem 1.5rem;
                border-radius: 9999px;
                font-size: 0.875rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.2s;
                background: rgba(255, 255, 255, 0.05);
                color: #94a3b8;
                border: 1px solid transparent;
            }
            .tab-btn:hover {
                color: white;
                background: rgba(255, 255, 255, 0.1);
            }
            .tab-btn.active {
                background: rgba(168, 85, 247, 0.2);
                color: #d8b4fe;
                border-color: rgba(168, 85, 247, 0.5);
                box-shadow: 0 0 20px rgba(168, 85, 247, 0.15);
            }
            
            .glass-button {
                background: linear-gradient(to right, #9333ea, #db2777);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.75rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                transition: all 0.2s;
                box-shadow: 0 4px 15px rgba(147, 51, 234, 0.3);
            }
            .glass-button:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(147, 51, 234, 0.4);
            }
            
            .glass-button-secondary {
                background: rgba(30, 41, 59, 0.5);
                color: #cbd5e1;
                padding: 0.75rem 1.5rem;
                border-radius: 0.75rem;
                font-weight: 500;
                border: 1px solid rgba(255, 255, 255, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                transition: all 0.2s;
            }
            .glass-button-secondary:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }
        ';

        require __DIR__ . '/../views/profile.view.php';
    }

    /**
     * Get Current User
     */
    private function getCurrentUser()
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username, email, avatar, is_admin, created_at, last_login FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }

    /**
     * Handle Update Profile
     */
    private function handleUpdateProfile()
    {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['success' => '', 'error' => 'Ошибка безопасности (CSRF)'];
        }

        $username = sanitizeInput($_POST['username'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');

        if (empty($username) || empty($email)) {
            return ['success' => '', 'error' => 'Заполните все поля'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => '', 'error' => 'Некорректный email'];
        }

        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['success' => '', 'error' => 'Имя пользователя должно быть от 3 до 50 символов'];
        }

        try {
            $db = getDB();

            // Получаем текущий email
            $stmt = $db->prepare("SELECT email, username FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $currentUser = $stmt->fetch();
            $currentEmail = $currentUser['email'];
            $emailChanged = ($email !== $currentEmail);

            // Проверяем, не занято ли имя пользователя другим пользователем
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $stmt->execute([$username, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                return ['success' => '', 'error' => 'Это имя пользователя уже занято'];
            }

            // Если email изменился
            if ($emailChanged) {
                // Проверяем, не занят ли email другим пользователем
                $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $_SESSION['user_id']]);
                if ($stmt->fetch()) {
                    return ['success' => '', 'error' => 'Этот email уже используется'];
                }

                // Создаем токен подтверждения
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

                // Удаляем старые токены для этого пользователя
                $stmt = $db->prepare("DELETE FROM email_verifications WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);

                // Сохраняем новый токен
                $stmt = $db->prepare("
                    INSERT INTO email_verifications (user_id, new_email, token, expires_at)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$_SESSION['user_id'], $email, $token, $expiresAt]);

                // Отправляем письмо подтверждения
                sendEmailVerification($email, $username, $token);

                // Обновляем только username (email обновится после подтверждения)
                $stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->execute([$username, $_SESSION['user_id']]);

                // Обновляем имя в сессии
                $_SESSION['username'] = $username;

                // Логируем
                logActivity(ActivityActions::USER_UPDATE_PROFILE, sprintf('Запрошено изменение email на %s', $email), 'user', $_SESSION['user_id']);

                // Уведомление
                notify($_SESSION['user_id'], NotificationTypes::INFO, 'Подтвердите email', 'Письмо с подтверждением отправлено на новый адрес', '/profile', NotificationIcons::INFO);

                return ['success' => 'Имя обновлено. Проверьте новый email для подтверждения изменения адреса.', 'error' => ''];
            } else {
                // Обновляем только username
                $stmt = $db->prepare("UPDATE users SET username = ? WHERE id = ?");
                $stmt->execute([$username, $_SESSION['user_id']]);

                // Обновляем имя в сессии
                $_SESSION['username'] = $username;

                // Логируем
                logActivity(ActivityActions::USER_UPDATE_PROFILE, sprintf('Обновлен username: %s', $username), 'user', $_SESSION['user_id']]);

                // Уведомление
                notify($_SESSION['user_id'], NotificationTypes::SUCCESS, 'Профиль обновлен', 'Ваше имя пользователя успешно обновлено', '/profile', NotificationIcons::SUCCESS);

                return ['success' => 'Профиль успешно обновлен', 'error' => ''];
            }

        } catch (PDOException $e) {
            error_log("Profile update error: " . $e->getMessage());
            return ['success' => '', 'error' => 'Ошибка базы данных. Попробуйте позже.'];
        }
    }

    /**
     * Handle Change Password
     */
    private function handleChangePassword()
    {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['success' => '', 'error' => 'Ошибка безопасности (CSRF)'];
        }

        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            return ['success' => '', 'error' => 'Заполните все поля'];
        }

        if ($new_password !== $confirm_password) {
            return ['success' => '', 'error' => 'Новые пароли не совпадают'];
        }

        if (strlen($new_password) < 8) {
            return ['success' => '', 'error' => 'Новый пароль должен быть не менее 8 символов'];
        }

        try {
            $db = getDB();

            // Проверяем текущий пароль
            $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($current_password, $user['password_hash'])) {
                return ['success' => '', 'error' => 'Неверный текущий пароль'];
            }

            // Обновляем пароль
            $new_hash = password_hash($new_password, PASSWORD_ARGON2ID);
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$new_hash, $_SESSION['user_id']]);

            // Логируем смену пароля
            logActivity(ActivityActions::USER_CHANGE_PASSWORD, "Пользователь изменил пароль", 'user', $_SESSION['user_id']);

            // Отправляем уведомление
            notify($_SESSION['user_id'], NotificationTypes::WARNING, 'Пароль изменен', 'Ваш пароль был успешно изменен. Если это были не вы, немедленно свяжитесь с поддержкой.', '/profile', NotificationIcons::WARNING);

            return ['success' => 'Пароль успешно изменен', 'error' => ''];

        } catch (PDOException) {
            return ['success' => '', 'error' => 'Ошибка базы данных. Попробуйте позже.'];
        }
    }

    /**
     * Handle Upload Avatar
     *
     * @return array Data array
     */
    private function handleUploadAvatar(): array
    {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['success' => '', 'error' => 'Ошибка безопасности (CSRF)'];
        }

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => '', 'error' => 'Файл не выбран'];
        }

        try {
            $imageUploader = new ImageUploader();
            $result = $imageUploader->uploadAvatar();

            if (!$result['success']) {
                return ['success' => '', 'error' => $result['error']];
            }

            $db = getDB();

            // Получить старый аватар для удаления
            $stmt = $db->prepare("SELECT avatar FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $oldAvatar = $stmt->fetchColumn();

            // Обновить аватар в БД
            $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            $stmt->execute([$result['filename'], $_SESSION['user_id']]);

            // Удалить старый аватар
            if ($oldAvatar) {
                $imageUploader->deleteAvatar($oldAvatar);
            }

            // Логируем
            logActivity(ActivityActions::USER_UPDATE_PROFILE, "Загружен новый аватар", 'user', $_SESSION['user_id']);

            // Уведомление
            notify($_SESSION['user_id'], NotificationTypes::SUCCESS, 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', NotificationIcons::SUCCESS);

            return ['success' => 'Аватар успешно загружен', 'error' => ''];

        } catch (Exception $exception) {
            error_log("Avatar upload error: " . $exception->getMessage());
            return ['success' => '', 'error' => 'Ошибка загрузки аватара'];
        }
    }

    /**
     * Handle Delete Avatar
     */
    private function handleDeleteAvatar()
    {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['success' => '', 'error' => 'Ошибка безопасности (CSRF)'];
        }

        try {
            $db = getDB();

            // Получить текущий аватар
            $stmt = $db->prepare("SELECT avatar FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $avatar = $stmt->fetchColumn();

            if (!$avatar) {
                return ['success' => '', 'error' => 'Аватар не установлен'];
            }

            // Удалить файл
            $imageUploader = new ImageUploader();
            $imageUploader->deleteAvatar($avatar);

            // Обновить БД
            $stmt = $db->prepare("UPDATE users SET avatar = NULL WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);

            // Логируем
            logActivity(ActivityActions::USER_UPDATE_PROFILE, "Удален аватар", 'user', $_SESSION['user_id']);

            return ['success' => 'Аватар удален', 'error' => ''];

        } catch (Exception $exception) {
            error_log("Avatar delete error: " . $exception->getMessage());
            return ['success' => '', 'error' => 'Ошибка удаления аватара'];
        }
    }
}
