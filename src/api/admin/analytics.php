<?php

/**
 * Admin Analytics API
 *
 * Provides statistics and analytics data for admin dashboard.
 *
 * @package AuraUI\API\Admin
 */

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Доступ запрещён']);
    exit;
}

$action = $_GET['action'] ?? '';

try {
    $db = getDB();
    $db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    switch ($action) {
        // Регистрации по дням (последние 30 дней)
        case 'registrations_chart':
            $stmt = $db->query("
                SELECT DATE(created_at) as date, COUNT(*) as count
                FROM users
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date ASC
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Регистрации по неделям (последние 12 недель)
        case 'registrations_weekly':
            $stmt = $db->query("
                SELECT 
                    YEARWEEK(created_at, 1) as week,
                    MIN(DATE(created_at)) as week_start,
                    COUNT(*) as count
                FROM users
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 WEEK)
                GROUP BY YEARWEEK(created_at, 1)
                ORDER BY week ASC
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Активные пользователи за период
        case 'active_users':
            $period = $_GET['period'] ?? '7'; // дней
            $stmt = $db->prepare("
                SELECT COUNT(DISTINCT user_id) as active_users
                FROM activity_logs
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ");
            $stmt->execute([$period]);
            $active = $stmt->fetch()['active_users'];
            
            // Всего пользователей
            $total = $db->query("SELECT COUNT(*) as total FROM users")->fetch()['total'];
            
            // Входы за период
            $stmt = $db->prepare("
                SELECT COUNT(*) as logins
                FROM login_attempts
                WHERE success = 1 AND attempted_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ");
            $stmt->execute([$period]);
            $logins = $stmt->fetch()['logins'];
            
            echo json_encode([
                'success' => true,
                'data' => [
                    'active_users' => (int)$active,
                    'total_users' => (int)$total,
                    'logins' => (int)$logins,
                    'activity_rate' => $total > 0 ? round(($active / $total) * 100, 1) : 0
                ]
            ]);
            break;
            
        // Неподтверждённые аккаунты
        case 'unverified_accounts':
            $stmt = $db->query("
                SELECT id, username, email, created_at,
                       TIMESTAMPDIFF(HOUR, created_at, NOW()) as hours_ago
                FROM users
                WHERE email_verified = 0
                ORDER BY created_at DESC
                LIMIT 50
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Общая статистика
        case 'overview':
            $stats = [];
            
            // Пользователи
            $stats['total_users'] = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $stats['verified_users'] = $db->query("SELECT COUNT(*) FROM users WHERE email_verified = 1")->fetchColumn();
            $stats['unverified_users'] = $db->query("SELECT COUNT(*) FROM users WHERE email_verified = 0")->fetchColumn();
            $stats['blocked_users'] = $db->query("SELECT COUNT(*) FROM users WHERE locked_until > NOW()")->fetchColumn();
            $stats['admins'] = $db->query("SELECT COUNT(*) FROM users WHERE is_admin = 1")->fetchColumn();
            
            // Регистрации
            $stats['registrations_today'] = $db->query("SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()")->fetchColumn();
            $stats['registrations_week'] = $db->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
            $stats['registrations_month'] = $db->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
            
            // Безопасность
            $stats['blocked_ips'] = $db->query("SELECT COUNT(*) FROM blocked_ips WHERE is_permanent = 1 OR expires_at > NOW()")->fetchColumn();
            $stats['failed_logins_24h'] = $db->query("SELECT COUNT(*) FROM login_attempts WHERE success = 0 AND attempted_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();
            $stats['active_sessions'] = $db->query("SELECT COUNT(*) FROM user_sessions WHERE is_active = 1")->fetchColumn();
            
            echo json_encode(['success' => true, 'data' => $stats]);
            break;
            
        // Топ активных пользователей
        case 'top_active':
            $stmt = $db->query("
                SELECT u.id, u.username, u.last_login, COUNT(al.id) as actions
                FROM users u
                LEFT JOIN activity_logs al ON u.id = al.user_id AND al.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY u.id
                ORDER BY actions DESC
                LIMIT 10
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Входы по часам (за последние 24 часа)
        case 'logins_hourly':
            $stmt = $db->query("
                SELECT 
                    HOUR(attempted_at) as hour,
                    SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as success,
                    SUM(CASE WHEN success = 0 THEN 1 ELSE 0 END) as failed
                FROM login_attempts
                WHERE attempted_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
                GROUP BY HOUR(attempted_at)
                ORDER BY hour ASC
            ");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
    }
    
} catch (PDOException $e) {
    error_log("Admin analytics API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
}
