<?php

namespace AuraUI\Controllers;

use AuraUI\Helpers\EmailValidator;
use PDOException;

/**
 *  Register Controller
 *
 * @package AuraUI\Controllers
 */
class RegisterController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        $error = '';
        $success = '';
        $formData = ['username' => '', 'email' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->handleRegister();
            $error = $result['error'];
            $success = $result['success'];
            $formData = $result['formData'];
        }

        $csrf_token = generateCSRFToken();
        require __DIR__ . '/../views/register.view.php';
    }

    /**
     * Handle Register
     *
     * @return array Data array
     */
    private function handleRegister(): array
    {
        $success = '';

        $username = sanitizeInput($_POST['username'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $formData = ['username' => $username, 'email' => $email];

        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['error' => 'Ошибка безопасности', 'success' => '', 'formData' => $formData];
        }

        if (empty($username) || empty($email) || empty($password)) {
            return ['error' => 'Все поля обязательны', 'success' => '', 'formData' => $formData];
        }

        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['error' => 'Имя пользователя должно быть от 3 до 50 символов', 'success' => '', 'formData' => $formData];
        }

        // Валидация email (проверка формата, временных доменов и существования)
        $emailValidation = EmailValidator::validate($email);
        if (!$emailValidation['valid']) {
            return ['error' => $emailValidation['error'], 'success' => '', 'formData' => $formData];
        }

        if (strlen($password) < 8) {
            return ['error' => 'Пароль должен быть минимум 8 символов', 'success' => '', 'formData' => $formData];
        }

        if ($password !== $password_confirm) {
            return ['error' => 'Пароли не совпадают', 'success' => '', 'formData' => $formData];
        }

        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);

            if ($stmt->fetch()) {
                return ['error' => 'Пользователь с таким именем или email уже существует', 'success' => '', 'formData' => $formData];
            }

            // Генерируем токен подтверждения
            $verificationToken = bin2hex(random_bytes(32));
            $tokenExpires = date('Y-m-d H:i:s', strtotime('+24 hours'));

            $password_hash = password_hash($password, PASSWORD_ARGON2ID);
            $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, email_verified, email_verification_token, email_verification_expires) VALUES (?, ?, ?, 0, ?, ?)");
            $stmt->execute([$username, $email, $password_hash, $verificationToken, $tokenExpires]);

            // Отправляем письмо с подтверждением вместо welcome email
            sendRegistrationVerificationEmail($email, $username, $verificationToken);

            // Уведомляем админов о новой регистрации
            $userId = $db->lastInsertId();
            $notifier = new \AuraUI\Helpers\AdminNotifier();
            $notifier->notifyNewRegistration($userId, $username, $email);

            $success = 'Регистрация почти завершена! Проверьте email и перейдите по ссылке для подтверждения.';
            $formData = ['username' => '', 'email' => ''];
        } catch (PDOException) {
            return ['error' => 'Ошибка регистрации', 'success' => '', 'formData' => $formData];
        }

        return ['error' => '', 'success' => $success, 'formData' => $formData];
    }
}
