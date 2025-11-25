<?php

/**
 * Admin Notifications API
 *
 * Handles admin notifications for registrations, security alerts, reports.
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

$action = $_REQUEST['action'] ?? '';

try {
    $db = getDB();
    $db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    switch ($action) {
        // Получить уведомления админа
        case 'get':
            $limit = (int)($_GET['limit'] ?? 20);
            $offset = (int)($_GET['offset'] ?? 0);
            $type = $_GET['type'] ?? '';
            $unreadOnly = isset($_GET['unread_only']);
            
            $sql = "SELECT * FROM admin_notifications WHERE 1=1";
            $params = [];
            
            if ($type) {
                $sql .= " AND type = ?";
                $params[] = $type;
            }
            
            if ($unreadOnly) {
                $sql .= " AND is_read = FALSE";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $notifications = $stmt->fetchAll();
            
            // Подсчёт непрочитанных
            $countStmt = $db->query("SELECT COUNT(*) FROM admin_notifications WHERE is_read = FALSE");
            $unreadCount = $countStmt->fetchColumn();
            
            echo json_encode([
                'success' => true, 
                'data' => $notifications,
                'unread_count' => (int)$unreadCount
            ]);
            break;

        // Отметить как прочитанное
        case 'mark_read':
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id > 0) {
                $stmt = $db->prepare("UPDATE admin_notifications SET is_read = TRUE WHERE id = ?");
                $stmt->execute([$id]);
            } else {
                // Отметить все как прочитанные
                $db->exec("UPDATE admin_notifications SET is_read = TRUE");
            }
            
            echo json_encode(['success' => true, 'message' => 'Отмечено как прочитанное']);
            break;
            
        // Удалить уведомление
        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            
            if ($id > 0) {
                $stmt = $db->prepare("DELETE FROM admin_notifications WHERE id = ?");
                $stmt->execute([$id]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Уведомление удалено']);
            break;
            
        // Очистить старые уведомления
        case 'clear_old':
            $days = (int)($_POST['days'] ?? 30);
            $stmt = $db->prepare("DELETE FROM admin_notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)");
            $stmt->execute([$days]);
            $deleted = $stmt->rowCount();
            
            echo json_encode(['success' => true, 'message' => "Удалено {$deleted} уведомлений"]);
            break;
            
        // Получить настройки уведомлений
        case 'get_settings':
            $stmt = $db->prepare("SELECT * FROM admin_notification_settings WHERE admin_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $settings = $stmt->fetch();
            
            if (!$settings) {
                // Создаём настройки по умолчанию
                $stmt = $db->prepare("INSERT INTO admin_notification_settings (admin_id) VALUES (?)");
                $stmt->execute([$_SESSION['user_id']]);
                
                $settings = [
                    'notify_new_registration' => true,
                    'notify_suspicious_activity' => true,
                    'notify_failed_logins' => true,
                    'email_reports' => false,
                    'email_report_frequency' => 'daily'
                ];
            }
            
            echo json_encode(['success' => true, 'data' => $settings]);
            break;
            
        // Сохранить настройки уведомлений
        case 'save_settings':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $notifyReg = isset($_POST['notify_new_registration']) ? 1 : 0;
            $notifySuspicious = isset($_POST['notify_suspicious_activity']) ? 1 : 0;
            $notifyFailed = isset($_POST['notify_failed_logins']) ? 1 : 0;
            $emailReports = isset($_POST['email_reports']) ? 1 : 0;
            $frequency = in_array($_POST['email_report_frequency'] ?? '', ['daily', 'weekly', 'monthly']) 
                ? $_POST['email_report_frequency'] : 'daily';
            
            $stmt = $db->prepare("
                INSERT INTO admin_notification_settings 
                (admin_id, notify_new_registration, notify_suspicious_activity, notify_failed_logins, email_reports, email_report_frequency)
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                notify_new_registration = VALUES(notify_new_registration),
                notify_suspicious_activity = VALUES(notify_suspicious_activity),
                notify_failed_logins = VALUES(notify_failed_logins),
                email_reports = VALUES(email_reports),
                email_report_frequency = VALUES(email_report_frequency)
            ");
            $stmt->execute([$_SESSION['user_id'], $notifyReg, $notifySuspicious, $notifyFailed, $emailReports, $frequency]);
            
            echo json_encode(['success' => true, 'message' => 'Настройки сохранены']);
            break;
            
        // Отправить отчёт на email
        case 'send_report':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            // Собираем статистику
            $stats = $db->query("SELECT 
                (SELECT COUNT(*) FROM users) as total_users,
                (SELECT COUNT(*) FROM users WHERE DATE(created_at) = CURDATE()) as new_today,
                (SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as new_week,
                (SELECT COUNT(*) FROM login_attempts WHERE success = 0 AND attempted_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) as failed_logins_24h,
                (SELECT COUNT(*) FROM blocked_ips) as blocked_ips
            ")->fetch();
            
            // Получаем email админа
            $stmt = $db->prepare("SELECT email FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $adminEmail = $stmt->fetchColumn();
            
            $html = "
                <h2>Отчёт AuraUI Admin</h2>
                <p>Дата: " . date('Y-m-d H:i:s') . "</p>
                <h3>Статистика:</h3>
                <ul>
                    <li>Всего пользователей: {$stats['total_users']}</li>
                    <li>Новых сегодня: {$stats['new_today']}</li>
                    <li>Новых за неделю: {$stats['new_week']}</li>
                    <li>Неудачных входов за 24ч: {$stats['failed_logins_24h']}</li>
                    <li>Заблокированных IP: {$stats['blocked_ips']}</li>
                </ul>
            ";
            
            $result = sendEmail($adminEmail, 'Отчёт AuraUI Admin - ' . date('Y-m-d'), $html);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => "Отчёт отправлен на {$adminEmail}"]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Ошибка отправки отчёта']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
    }
    
} catch (PDOException $e) {
    error_log("Admin notifications API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
}
