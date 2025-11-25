<?php

require_once __DIR__ . '/../../config.php';

use AuraUI\Helpers\ActivityActions;
use AuraUI\Helpers\ImageUploader;

use function logActivity;

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

try {
    $db = getDB();

    // Получить текущий аватар
    $stmt = $db->prepare("SELECT avatar, username FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user['avatar']) {
        echo json_encode(['success' => false, 'error' => 'Аватар не установлен']);
        exit;
    }

    // Удалить файл
    $uploader = new ImageUploader();
    $uploader->deleteAvatar($user['avatar']);

    // Обновить БД
    $stmt = $db->prepare("UPDATE users SET avatar = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    // Логируем
    logActivity(ActivityActions::USER_UPDATE_PROFILE, "Удален аватар", 'user', $_SESSION['user_id']);

    // Получить первую букву для дефолтного аватара
    $initial = strtoupper(mb_substr($user['username'], 0, 1));

    echo json_encode([
        'success' => true,
        'message' => 'Аватар удален',
        'initial' => $initial
    ]);

} catch (Exception $e) {
    error_log("Avatar delete error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка удаления аватара']);
}
