<?php

/**
 * Admin Users API
 *
 * Handles user management actions: block, unblock, toggle admin, reset password, verify email.
 *
 * @package AuraUI\API\Admin
 */

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

// Проверка авторизации и прав админа
if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Доступ запрещён']);
    exit;
}

// Проверка CSRF
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
    exit;
}

$action = $_POST['action'] ?? '';
$userId = (int)($_POST['user_id'] ?? 0);

if (!$userId) {
    echo json_encode(['success' => false, 'error' => 'ID пользователя не указан']);
    exit;
}

// Нельзя изменять самого себя (для некоторых действий)
$currentUserId = $_SESSION['user_id'];

try {
    $db = getDB();
    
    // Проверяем существование пользователя
    $stmt = $db->prepare("SELECT id, username, email, is_admin, locked_until, email_verified FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'error' => 'Пользователь не найден']);
        exit;
    }
    
    switch ($action) {
        case 'block':
            if ($userId === $currentUserId) {
                echo json_encode(['success' => false, 'error' => 'Нельзя заблокировать себя']);
                exit;
            }
            
            // Блокируем до 2037 года (максимум для MySQL TIMESTAMP)
            $lockedUntil = '2037-12-31 23:59:59';
            $stmt = $db->prepare("UPDATE users SET locked_until = ?, failed_attempts = 999 WHERE id = ?");
            $stmt->execute([$lockedUntil, $userId]);
            
            echo json_encode([
                'success' => true, 
                'message' => "Пользователь {$user['username']} заблокирован"
            ]);
            break;
            
        case 'unblock':
            $stmt = $db->prepare("UPDATE users SET locked_until = NULL, failed_attempts = 0 WHERE id = ?");
            $stmt->execute([$userId]);
            
            echo json_encode([
                'success' => true, 
                'message' => "Пользователь {$user['username']} разблокирован"
            ]);
            break;
            
        case 'toggle_admin':
            if ($userId === $currentUserId) {
                echo json_encode(['success' => false, 'error' => 'Нельзя изменить свои права']);
                exit;
            }
            
            $newAdminStatus = $user['is_admin'] ? 0 : 1;
            $stmt = $db->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
            $stmt->execute([$newAdminStatus, $userId]);
            
            $statusText = $newAdminStatus ? 'назначен администратором' : 'лишён прав администратора';
            echo json_encode([
                'success' => true, 
                'message' => "Пользователь {$user['username']} {$statusText}",
                'is_admin' => $newAdminStatus
            ]);
            break;
            
        case 'reset_password':
            // Генерируем новый пароль
            $newPassword = bin2hex(random_bytes(8)); // 16 символов
            $passwordHash = password_hash($newPassword, PASSWORD_ARGON2ID);
            
            $stmt = $db->prepare("UPDATE users SET password_hash = ?, failed_attempts = 0, locked_until = NULL WHERE id = ?");
            $stmt->execute([$passwordHash, $userId]);
            
            // Отправляем новый пароль на email
            $subject = 'Ваш пароль сброшен - AuraUI';
            $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2>Сброс пароля</h2>
                <p>Здравствуйте, {$user['username']}!</p>
                <p>Администратор сбросил ваш пароль. Ваш новый пароль:</p>
                <p style='font-size: 24px; font-weight: bold; background: #f0f0f0; padding: 15px; border-radius: 8px; text-align: center;'>{$newPassword}</p>
                <p>Рекомендуем сменить пароль после входа в систему.</p>
            </div>";
            
            sendEmail($user['email'], $subject, $html);
            
            echo json_encode([
                'success' => true, 
                'message' => "Пароль сброшен. Новый пароль отправлен на {$user['email']}"
            ]);
            break;
            
        case 'verify_email':
            $stmt = $db->prepare("UPDATE users SET email_verified = 1, email_verification_token = NULL, email_verification_expires = NULL WHERE id = ?");
            $stmt->execute([$userId]);
            
            echo json_encode([
                'success' => true, 
                'message' => "Email пользователя {$user['username']} подтверждён"
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
    }
    
} catch (PDOException $e) {
    error_log("Admin users API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
}
