<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../email.php';

class RegisterController {
    
    public function index() {
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

    private function handleRegister() {
        $error = '';
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

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Неверный формат email', 'success' => '', 'formData' => $formData];
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

            $password_hash = password_hash($password, PASSWORD_ARGON2ID);
            $stmt = $db->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password_hash]);
            
            sendWelcomeEmail($email, $username);
            
            $success = 'Регистрация успешна! Проверьте email и можете войти.';
            $formData = ['username' => '', 'email' => ''];
        } catch (PDOException $e) {
            return ['error' => 'Ошибка регистрации', 'success' => '', 'formData' => $formData];
        }

        return ['error' => '', 'success' => $success, 'formData' => $formData];
    }
}
