<?php

namespace AuraUI\Controllers;

use PDOException;

/**
 *  Forgot Password Controller
 *
 * @package AuraUI\Controllers
 */
class ForgotPasswordController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        // Разрешаем доступ всем (даже авторизованным пользователям)
        // Они могут захотеть сбросить пароль

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->handleForgotPassword();
            $error = $result['error'];
            $success = $result['success'];
        }

        $pageTitle = 'Восстановление пароля | AuraUI';
        require __DIR__ . '/../views/forgot_password.view.php';
    }

    /**
     * Handle Forgot Password
     */
    private function handleForgotPassword()
    {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['error' => 'Ошибка безопасности', 'success' => ''];
        }

        $email = sanitizeInput($_POST['email'] ?? '');

        if (empty($email)) {
            return ['error' => 'Введите email', 'success' => ''];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Некорректный email', 'success' => ''];
        }

        try {
            $db = getDB();

            $stmt = $db->prepare("SELECT id, username FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires_at = date('Y-m-d H:i:s', time() + 3600);

                $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
                $stmt->execute([$user['id'], $token, $expires_at]);

                sendPasswordResetEmail($email, $user['username'], $token);
            }

            return ['error' => '', 'success' => 'Если email существует, на него отправлена ссылка для восстановления пароля.'];

        } catch (PDOException $pdoException) {
            error_log("Forgot password error: " . $pdoException->getMessage());
            return ['error' => 'Ошибка обработки запроса', 'success' => ''];
        }
    }
}
