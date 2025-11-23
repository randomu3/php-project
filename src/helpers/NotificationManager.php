<?php
require_once __DIR__ . '/../config.php';

class NotificationManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Создать уведомление
     */
    public function create($userId, $type, $title, $message, $link = null, $icon = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO notifications (user_id, type, title, message, link, icon)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $userId,
                $type,
                $title,
                $message,
                $link,
                $icon
            ]);
        } catch (PDOException $e) {
            error_log("NotificationManager error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Получить уведомления пользователя
     */
    public function getUserNotifications($userId, $limit = 10, $unreadOnly = false) {
        $whereClause = $unreadOnly ? "AND is_read = 0" : "";
        
        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE user_id = ? $whereClause
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Получить количество непрочитанных уведомлений
     */
    public function getUnreadCount($userId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM notifications
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    /**
     * Отметить уведомление как прочитанное
     */
    public function markAsRead($notificationId, $userId) {
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1, read_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notificationId, $userId]);
    }
    
    /**
     * Отметить все уведомления как прочитанные
     */
    public function markAllAsRead($userId) {
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1, read_at = NOW()
            WHERE user_id = ? AND is_read = 0
        ");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Удалить уведомление
     */
    public function delete($notificationId, $userId) {
        $stmt = $this->db->prepare("
            DELETE FROM notifications
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notificationId, $userId]);
    }
    
    /**
     * Удалить все прочитанные уведомления
     */
    public function deleteAllRead($userId) {
        $stmt = $this->db->prepare("
            DELETE FROM notifications
            WHERE user_id = ? AND is_read = 1
        ");
        return $stmt->execute([$userId]);
    }
    
    /**
     * Удалить старые уведомления
     */
    public function cleanOldNotifications($days = 30) {
        $stmt = $this->db->prepare("
            DELETE FROM notifications
            WHERE is_read = 1 AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
    
    /**
     * Отправить уведомление всем пользователям
     */
    public function notifyAll($type, $title, $message, $link = null, $icon = null) {
        $stmt = $this->db->query("SELECT id FROM users");
        $users = $stmt->fetchAll();
        
        $count = 0;
        foreach ($users as $user) {
            if ($this->create($user['id'], $type, $title, $message, $link, $icon)) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Отправить уведомление пользователям с определенной ролью
     */
    public function notifyRole($roleName, $type, $title, $message, $link = null, $icon = null) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT u.id
            FROM users u
            JOIN user_roles ur ON u.id = ur.user_id
            JOIN roles r ON ur.role_id = r.id
            WHERE r.name = ?
        ");
        $stmt->execute([$roleName]);
        $users = $stmt->fetchAll();
        
        $count = 0;
        foreach ($users as $user) {
            if ($this->create($user['id'], $type, $title, $message, $link, $icon)) {
                $count++;
            }
        }
        
        return $count;
    }
}

// Глобальные функции для удобства
function notify($userId, $type, $title, $message, $link = null, $icon = null) {
    $nm = new NotificationManager();
    return $nm->create($userId, $type, $title, $message, $link, $icon);
}

function getUnreadNotificationsCount() {
    if (!isLoggedIn()) return 0;
    $nm = new NotificationManager();
    return $nm->getUnreadCount($_SESSION['user_id']);
}

// Типы уведомлений
class NotificationTypes {
    const INFO = 'info';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const ERROR = 'error';
    const SYSTEM = 'system';
}

// Иконки для уведомлений
class NotificationIcons {
    const INFO = 'info';
    const SUCCESS = 'check-circle';
    const WARNING = 'alert-triangle';
    const ERROR = 'alert-circle';
    const SYSTEM = 'settings';
    const USER = 'user';
    const EMAIL = 'mail';
    const BELL = 'bell';
}
