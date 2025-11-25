<?php

require_once __DIR__ . '/../../config.php';

use AuraUI\Helpers\NotificationManager;

header('Content-Type: application/json');

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$notificationId = $_POST['id'] ?? null;

if (!$notificationId) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing notification ID']);
    exit;
}

$nm = new NotificationManager();
$success = $nm->markAsRead($notificationId, $_SESSION['user_id']);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to mark as read']);
}
