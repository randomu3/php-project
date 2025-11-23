<?php
require_once __DIR__ . '/../config.php';

class ResetPasswordController {
    
    public function index() {
        // Разрешаем доступ всем (даже авторизованным)
        // Пользователь может сбросить пароль по ссылке из email
        
        $token = $_GET['token'] ?? '';
        $error = '';
        $success = '';
        $valid_token = false;
        
        if (empty($token)) {
            $error = 'Недействительная ссылка для восстановления пароля.';
        } else {
            $valid_token = $this->validateToken($token);
            if (!$valid_token) {
                $error = 'Ссылка для восстановления пароля недействительна или истекла.';
            }
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $valid_token) {
            $result = $this->handleResetPassword($token);
            $error = $result['error'];
            $success = $result['success'];
            if ($success) {
                $valid_token = false;
            }
        }
        
        $pageTitle = 'Сброс пароля | AuraUI';
        require __DIR__ . '/../views/reset_password.view.php';
    }
    
    private function validateToken($token) {
        try {
            $db = getDB();
            $stmt = $db->prepare("
                SELECT pr.user_id 
                FROM password_resets pr 
                WHERE pr.token = ? AND pr.used = FALSE AND pr.expires_at > NOW()
            ");
            $stmt->execute([$token]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    private function handleResetPassword($token) {
        if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            return ['error' => 'Ошибка безопасности', 'success' => ''];
        }
        
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        
        if (empty($password)) {
            return ['error' => 'Введите новый пароль', 'success' => ''];
        }
        
        if (strlen($password) < 8) {
            return ['error' => 'Пароль должен быть минимум 8 символов', 'success' => ''];
        }
        
        if ($password !== $password_confirm) {
            return ['error' => 'Пароли не совпадают', 'success' => ''];
        }
        
        try {
            $db = getDB();
            
            $stmt = $db->prepare("
                SELECT pr.id, pr.user_id 
                FROM password_resets pr 
                WHERE pr.token = ? AND pr.used = FALSE AND pr.expires_at > NOW()
            ");
            $stmt->execute([$token]);
            $reset = $stmt->fetch();
            
            if (!$reset) {
                return ['error' => 'Ссылка для восстановления пароля недействительна или истекла.', 'success' => ''];
            }
            
            $password_hash = password_hash($password, PASSWORD_ARGON2ID);
            $stmt = $db->prepare("UPDATE users SET password_hash = ?, failed_attempts = 0, locked_until = NULL WHERE id = ?");
            $stmt->execute([$password_hash, $reset['user_id']]);
            
            $stmt = $db->prepare("UPDATE password_resets SET used = TRUE WHERE id = ?");
            $stmt->execute([$reset['id']]);
            
            // Если пользователь был авторизован - выходим из системы
            if (isLoggedIn()) {
                session_destroy();
                session_start();
            }
            
            return ['error' => '', 'success' => 'Пароль успешно изменен! Можете войти с новым паролем.'];
            
        } catch (PDOException $e) {
            error_log("Reset password error: " . $e->getMessage());
            return ['error' => 'Ошибка обработки запроса', 'success' => ''];
        }
    }
}
