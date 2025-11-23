<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/ImageUploader.php';
require_once __DIR__ . '/../../helpers/ActivityLogger.php';
require_once __DIR__ . '/../../helpers/NotificationManager.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'CSRF token invalid']);
    exit;
}

if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'error' => 'Файл не выбран']);
    exit;
}

try {
    $uploader = new ImageUploader();
    $result = $uploader->uploadAvatar($_FILES['avatar'], $_SESSION['user_id']);
    
    if (!$result['success']) {
        echo json_encode(['success' => false, 'error' => $result['error']]);
        exit;
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
        $uploader->deleteAvatar($oldAvatar);
    }
    
    // Логируем
    logActivity(ActivityActions::USER_UPDATE_PROFILE, "Загружен новый аватар", 'user', $_SESSION['user_id']);
    
    // Уведомление
    notify($_SESSION['user_id'], NotificationTypes::SUCCESS, 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', NotificationIcons::SUCCESS);
    
    echo json_encode([
        'success' => true,
        'message' => 'Аватар успешно загружен',
        'avatar_url' => $result['path'] . '?t=' . time()
    ]);
    
} catch (Exception $e) {
    error_log("Avatar upload error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка загрузки аватара']);
}
