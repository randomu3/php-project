<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/CacheHelper.php';

header('Content-Type: application/json');

// Проверка авторизации
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit;
}

// Проверка прав админа
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

// Проверка CSRF
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Ошибка безопасности']);
    exit;
}

// Очистка OPcache
$result = CacheHelper::clearOPcache();

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'OPcache успешно очищен'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'OPcache не доступен или не включен'
    ]);
}
