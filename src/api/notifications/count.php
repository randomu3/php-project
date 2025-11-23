<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/NotificationManager.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized', 'count' => 0]);
    exit;
}

$nm = new NotificationManager();
$count = $nm->getUnreadCount($_SESSION['user_id']);

echo json_encode(['success' => true, 'count' => $count]);
