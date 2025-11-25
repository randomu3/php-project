<?php
/**
 * Session Check API
 *
 * Returns session status for client-side session monitoring.
 * Used by JavaScript to detect session expiration and auto-logout.
 *
 * @package AuraUI\API
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'valid' => false,
        'reason' => 'not_logged_in',
        'remaining' => 0
    ]);
    exit;
}

// Check session validity
$remaining = getSessionTimeRemaining();

if ($remaining <= 0) {
    // Session expired - force logout
    $userId = $_SESSION['user_id'];
    session_unset();
    session_destroy();
    
    echo json_encode([
        'valid' => false,
        'reason' => 'expired',
        'remaining' => 0
    ]);
    exit;
}

// Session is valid - update last activity
$_SESSION['LAST_ACTIVITY'] = time();

echo json_encode([
    'valid' => true,
    'remaining' => $remaining,
    'timeout' => SESSION_TIMEOUT,
    'warning_at' => 300 // Show warning when 5 minutes left
]);
