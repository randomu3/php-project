<?php
/**
 * Admin Sessions API
 * 
 * User sessions management
 * 
 * @package AuraUI\API\Admin
 */

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db = getDB();
$db->exec("SET NAMES utf8mb4");

switch ($action) {
    case 'list':
        listSessions($db);
        break;
    case 'terminate':
        terminateSession($db);
        break;
    case 'terminate_user':
        terminateUserSessions($db);
        break;
    case 'login_history':
        getLoginHistory($db);
        break;
    case 'stats':
        getSessionStats($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * List active sessions
 */
function listSessions($db): void
{
    $stmt = $db->query("
        SELECT s.*, u.username, u.email,
               SUBSTRING_INDEX(s.device_info, ' - ', 1) as device_type,
               SUBSTRING_INDEX(s.device_info, ' - ', -1) as browser
        FROM user_sessions s
        JOIN users u ON s.user_id = u.id
        WHERE s.is_active = 1
        ORDER BY s.last_activity DESC
        LIMIT 100
    ");
    
    $sessions = $stmt->fetchAll();
    
    foreach ($sessions as &$session) {
        $session['is_current'] = ($session['session_id'] === session_id());
        $session['time_ago'] = timeAgo($session['last_activity']);
    }
    
    echo json_encode(['success' => true, 'sessions' => $sessions]);
}

/**
 * Terminate specific session
 */
function terminateSession($db): void
{
    $sessionId = $_POST['session_id'] ?? '';
    
    if (empty($sessionId)) {
        echo json_encode(['success' => false, 'error' => 'Session ID required']);
        return;
    }
    
    // Don't allow terminating own session
    if ($sessionId === session_id()) {
        echo json_encode(['success' => false, 'error' => 'Cannot terminate your own session']);
        return;
    }
    
    $stmt = $db->prepare("UPDATE user_sessions SET is_active = 0 WHERE session_id = ?");
    $stmt->execute([$sessionId]);
    
    // Also destroy the PHP session file if possible
    $sessionPath = session_save_path() . '/sess_' . $sessionId;
    if (file_exists($sessionPath)) {
        unlink($sessionPath);
    }
    
    echo json_encode(['success' => true]);
}

/**
 * Terminate all sessions for a user
 */
function terminateUserSessions($db): void
{
    $userId = (int)($_POST['user_id'] ?? 0);
    
    if ($userId <= 0) {
        echo json_encode(['success' => false, 'error' => 'User ID required']);
        return;
    }
    
    // Don't terminate own sessions
    if ($userId === $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'error' => 'Cannot terminate your own sessions']);
        return;
    }
    
    $stmt = $db->prepare("UPDATE user_sessions SET is_active = 0 WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    echo json_encode(['success' => true]);
}

/**
 * Get login history
 */
function getLoginHistory($db): void
{
    $userId = (int)($_GET['user_id'] ?? 0);
    $limit = min((int)($_GET['limit'] ?? 50), 100);
    
    $sql = "
        SELECT s.*, u.username,
               SUBSTRING_INDEX(s.device_info, ' - ', 1) as device_type
        FROM user_sessions s
        JOIN users u ON s.user_id = u.id
    ";
    
    $params = [];
    if ($userId > 0) {
        $sql .= " WHERE s.user_id = ?";
        $params[] = $userId;
    }
    
    $sql .= " ORDER BY s.created_at DESC LIMIT ?";
    $params[] = $limit;
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    echo json_encode(['success' => true, 'history' => $stmt->fetchAll()]);
}

/**
 * Get session statistics
 */
function getSessionStats($db): void
{
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM user_sessions WHERE is_active = 1) as active_sessions,
            (SELECT COUNT(DISTINCT user_id) FROM user_sessions WHERE is_active = 1) as active_users,
            (SELECT COUNT(*) FROM user_sessions WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)) as sessions_24h,
            (SELECT COUNT(*) FROM user_sessions WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)) as sessions_7d
    ")->fetch();
    
    // Device breakdown
    $devices = $db->query("
        SELECT SUBSTRING_INDEX(device_info, ' - ', 1) as device_type, COUNT(*) as count
        FROM user_sessions
        WHERE is_active = 1 AND device_info IS NOT NULL
        GROUP BY SUBSTRING_INDEX(device_info, ' - ', 1)
    ")->fetchAll();
    
    // Browser breakdown
    $browsers = $db->query("
        SELECT SUBSTRING_INDEX(device_info, ' - ', -1) as browser, COUNT(*) as count
        FROM user_sessions
        WHERE is_active = 1 AND device_info IS NOT NULL
        GROUP BY SUBSTRING_INDEX(device_info, ' - ', -1)
        ORDER BY count DESC
        LIMIT 5
    ")->fetchAll();
    
    echo json_encode([
        'success' => true,
        'stats' => $stats,
        'devices' => $devices,
        'browsers' => $browsers
    ]);
}

/**
 * Format time ago
 */
function timeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'только что';
    if ($diff < 3600) return floor($diff / 60) . ' мин. назад';
    if ($diff < 86400) return floor($diff / 3600) . ' ч. назад';
    if ($diff < 604800) return floor($diff / 86400) . ' дн. назад';
    
    return date('d.m.Y H:i', $time);
}
