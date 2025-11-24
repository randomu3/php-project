<?php

namespace AuraUI\Helpers {

    use PDOException;

/**
 *  Notification Manager
 *
 * @package AuraUI\Helpers
 */
class NotificationManager
{
    /**
     * Db
     *
     * @var mixed
     */
    private $db;

    /**
     *   construct
     */
    public function __construct()
    {
        $this->db = getDB();
    }

    /**
     * Create
     *
     * @param  $userId User ID
     * @param  $type Parameter
     * @param  $title Parameter
     * @param  $message Message content
     * @param  $link Parameter
     * @param  $icon Parameter
     */
    public function create($userId, $type, $title, $message, $link = null, $icon = null)
    {
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
        } catch (PDOException $pdoException) {
            error_log("NotificationManager error: " . $pdoException->getMessage());
            return false;
        }
    }

    /**
     * Get User Notifications
     *
     * @param  $userId User ID
     * @param  $limit Parameter
     * @param  $unreadOnly Parameter
     */
    public function getUserNotifications($userId, $limit = 10, $unreadOnly = false)
    {
        $whereClause = $unreadOnly ? "AND is_read = 0" : "";

        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE user_id = ? {$whereClause}
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get Unread Count
     *
     * @param  $userId User ID
     */
    public function getUnreadCount($userId)
    {
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
     * Mark As Read
     *
     * @param  $notificationId Parameter
     * @param  $userId User ID
     */
    public function markAsRead($notificationId, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1, read_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notificationId, $userId]);
    }

    /**
     * Mark All As Read
     *
     * @param  $userId User ID
     */
    public function markAllAsRead($userId)
    {
        $stmt = $this->db->prepare("
            UPDATE notifications
            SET is_read = 1, read_at = NOW()
            WHERE user_id = ? AND is_read = 0
        ");
        return $stmt->execute([$userId]);
    }

    /**
     * Delete
     *
     * @param  $notificationId Parameter
     * @param  $userId User ID
     */
    public function delete($notificationId, $userId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM notifications
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$notificationId, $userId]);
    }

    /**
     * Delete All Read
     *
     * @param  $userId User ID
     */
    public function deleteAllRead($userId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM notifications
            WHERE user_id = ? AND is_read = 1
        ");
        return $stmt->execute([$userId]);
    }

    /**
     * Clean Old Notifications
     *
     * @param  $days Parameter
     */
    public function cleanOldNotifications($days = 30)
    {
        $stmt = $this->db->prepare("
            DELETE FROM notifications
            WHERE is_read = 1 AND created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }

    /**
     * Notify All
     *
     * @return int Integer value
     */
    public function notifyAll(): int
    {
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
     * Notify Role
     *
     * @return int Integer value
     */
    public function notifyRole(): int
    {
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

// Типы уведомлений
class NotificationTypes
{
    /**
     * Description
     *
     * @var mixed
     */
    public const INFO = 'info';

    /**
     * Description
     *
     * @var mixed
     */
    public const SUCCESS = 'success';

    /**
     * Description
     *
     * @var mixed
     */
    public const WARNING = 'warning';

    /**
     * Description
     *
     * @var mixed
     */
    public const ERROR = 'error';

    /**
     * Description
     *
     * @var mixed
     */
    public const SYSTEM = 'system';
}

// Иконки для уведомлений
class NotificationIcons
{
    public const INFO = 'info';

    public const SUCCESS = 'check-circle';

    public const WARNING = 'alert-triangle';

    public const ERROR = 'alert-circle';

    public const SYSTEM = 'settings';

    /**
     * User constant
     *
     * @const
     */
    public const USER = 'user';

    /**
     * Email constant
     *
     * @const
     */
    public const EMAIL = 'mail';

    /**
     * Bell constant
     *
     * @const
     */
    public const BELL = 'bell';
    }
}

namespace {
    /**
     * Global helper function for creating notifications
     *
     * @param int $userId User ID
     * @param string $type Notification type
     * @param string $title Title
     * @param string $message Message content
     * @param string|null $link Optional link
     * @param string|null $icon Optional icon
     *
     * @return bool True on success, false on failure
     */
    function notify($userId, $type, $title, $message, $link = null, $icon = null)
    {
        $nm = new \AuraUI\Helpers\NotificationManager();
        return $nm->create($userId, $type, $title, $message, $link, $icon);
    }

    /**
     * Get unread notifications count for current user
     *
     * @return int Unread count
     */
    function getUnreadNotificationsCount()
    {
        if (!isLoggedIn()) {
            return 0;
        }

        $nm = new \AuraUI\Helpers\NotificationManager();
        return $nm->getUnreadCount($_SESSION['user_id']);
    }
}
