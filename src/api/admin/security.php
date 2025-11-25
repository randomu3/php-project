<?php

/**
 * Admin Security API
 *
 * Handles security management: blocked IPs, login attempts, sessions.
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

$action = $_REQUEST['action'] ?? '';

try {
    $db = getDB();
    $db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    switch ($action) {
        // Получить список заблокированных IP
        case 'get_blocked_ips':
            $stmt = $db->query("
                SELECT bi.*, u.username as blocked_by_name 
                FROM blocked_ips bi 
                LEFT JOIN users u ON bi.blocked_by = u.id 
                ORDER BY bi.blocked_at DESC 
                LIMIT 100
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Заблокировать IP
        case 'block_ip':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $ip = filter_var($_POST['ip'] ?? '', FILTER_VALIDATE_IP);
            $reason = sanitizeInput($_POST['reason'] ?? '');
            $permanent = (bool)($_POST['permanent'] ?? false);
            $hours = (int)($_POST['hours'] ?? 24);
            
            if (!$ip) {
                echo json_encode(['success' => false, 'error' => 'Некорректный IP адрес']);
                exit;
            }
            
            $expiresAt = $permanent ? null : date('Y-m-d H:i:s', strtotime("+{$hours} hours"));
            
            $stmt = $db->prepare("
                INSERT INTO blocked_ips (ip_address, reason, blocked_by, expires_at, is_permanent) 
                VALUES (?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE reason = ?, blocked_by = ?, expires_at = ?, is_permanent = ?, blocked_at = NOW()
            ");
            $stmt->execute([
                $ip, $reason, $_SESSION['user_id'], $expiresAt, $permanent ? 1 : 0,
                $reason, $_SESSION['user_id'], $expiresAt, $permanent ? 1 : 0
            ]);
            
            echo json_encode(['success' => true, 'message' => "IP {$ip} заблокирован"]);
            break;
            
        // Разблокировать IP
        case 'unblock_ip':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $db->prepare("DELETE FROM blocked_ips WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true, 'message' => 'IP разблокирован']);
            break;
            
        // Получить попытки входа
        case 'get_login_attempts':
            $filter = $_GET['filter'] ?? 'all'; // all, failed, success
            $limit = min((int)($_GET['limit'] ?? 50), 200);
            
            $where = '';
            if ($filter === 'failed') $where = 'WHERE success = 0';
            if ($filter === 'success') $where = 'WHERE success = 1';
            
            $stmt = $db->query("
                SELECT * FROM login_attempts 
                {$where}
                ORDER BY attempted_at DESC 
                LIMIT {$limit}
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Получить статистику попыток входа по IP
        case 'get_ip_stats':
            $stmt = $db->query("
                SELECT 
                    ip_address,
                    COUNT(*) as total_attempts,
                    SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed_attempts,
                    SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as success_attempts,
                    MAX(attempted_at) as last_attempt
                FROM login_attempts 
                WHERE attempted_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY ip_address 
                HAVING failed_attempts >= 3
                ORDER BY failed_attempts DESC
                LIMIT 50
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Получить активные сессии
        case 'get_sessions':
            $userId = (int)($_GET['user_id'] ?? 0);
            
            $where = 'WHERE is_active = 1';
            $params = [];
            
            if ($userId) {
                $where .= ' AND user_id = ?';
                $params[] = $userId;
            }
            
            $stmt = $db->prepare("
                SELECT us.*, u.username 
                FROM user_sessions us 
                JOIN users u ON us.user_id = u.id 
                {$where}
                ORDER BY us.last_activity DESC 
                LIMIT 100
            ");
            $stmt->execute($params);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Завершить сессию
        case 'terminate_session':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $sessionId = (int)($_POST['session_id'] ?? 0);
            $stmt = $db->prepare("UPDATE user_sessions SET is_active = 0 WHERE id = ?");
            $stmt->execute([$sessionId]);
            
            echo json_encode(['success' => true, 'message' => 'Сессия завершена']);
            break;
            
        // Завершить все сессии пользователя
        case 'terminate_all_sessions':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $userId = (int)($_POST['user_id'] ?? 0);
            $exceptCurrent = (bool)($_POST['except_current'] ?? false);
            
            $sql = "UPDATE user_sessions SET is_active = 0 WHERE user_id = ?";
            $params = [$userId];
            
            if ($exceptCurrent && $userId == $_SESSION['user_id']) {
                $sql .= " AND session_id != ?";
                $params[] = session_id();
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            echo json_encode(['success' => true, 'message' => 'Все сессии завершены']);
            break;
            
        // Получить журнал активности
        case 'get_activity_log':
            $limit = min((int)($_GET['limit'] ?? 50), 200);
            $userId = (int)($_GET['user_id'] ?? 0);
            
            $where = '';
            $params = [];
            
            if ($userId) {
                $where = 'WHERE al.user_id = ?';
                $params[] = $userId;
            }
            
            $stmt = $db->prepare("
                SELECT al.*, u.username 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id 
                {$where}
                ORDER BY al.created_at DESC 
                LIMIT {$limit}
            ");
            $stmt->execute($params);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
    }
    
} catch (PDOException $e) {
    error_log("Admin security API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
}
