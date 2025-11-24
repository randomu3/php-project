<?php

namespace AuraUI\Helpers {

    use PDOException;

/**
 *  Activity Logger
 *
 * @package AuraUI\Helpers
 */
class ActivityLogger
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
     * Log
     *
     * @param  $action Parameter
     * @param  $description Parameter
     * @param  $entityType Parameter
     * @param  $entityId Parameter
     * @param  $userId User ID
     */
    public function log($action, $description = '', $entityType = null, $entityId = null, $userId = null)
    {
        // Если userId не указан, берем из сессии
        if ($userId === null && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        // Получаем IP и User Agent
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        try {
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, entity_type, entity_id, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            return $stmt->execute([
                $userId,
                $action,
                $description,
                $entityType,
                $entityId,
                $ipAddress,
                $userAgent
            ]);
        } catch (PDOException $pdoException) {
            // Логирование не должно ломать основной функционал
            error_log("ActivityLogger error: " . $pdoException->getMessage());
            return false;
        }
    }

    /**
     * Get User Logs
     *
     * @param  $userId User ID
     * @param  $limit Parameter
     * @param  $offset Parameter
     */
    public function getUserLogs($userId, $limit = 50, $offset = 0)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM activity_logs
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$userId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Get All Logs
     *
     * @param  $limit Parameter
     * @param  $offset Parameter
     * @param array $filters Parameter
     */
    public function getAllLogs($limit = 100, $offset = 0, array $filters = [])
    {
        $where = [];
        $params = [];

        if (!empty($filters['user_id'])) {
            $where[] = "user_id = ?";
            $params[] = $filters['user_id'];
        }

        if (!empty($filters['action'])) {
            $where[] = "action = ?";
            $params[] = $filters['action'];
        }

        if (!empty($filters['date_from'])) {
            $where[] = "created_at >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $where[] = "created_at <= ?";
            $params[] = $filters['date_to'];
        }

        $whereClause = $where === [] ? "" : "WHERE " . implode(" AND ", $where);

        $stmt = $this->db->prepare("
            SELECT al.*, u.username
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            {$whereClause}
            ORDER BY al.created_at DESC
            LIMIT ? OFFSET ?
        ");

        $params[] = $limit;
        $params[] = $offset;

        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get Stats
     *
     * @param  $days Parameter
     */
    public function getStats($days = 7)
    {
        $stmt = $this->db->prepare("
            SELECT 
                action,
                COUNT(*) as count,
                DATE(created_at) as date
            FROM activity_logs
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY action, DATE(created_at)
            ORDER BY date DESC, count DESC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    /**
     * Clean Old Logs
     *
     * @param  $days Parameter
     */
    public function cleanOldLogs($days = 90)
    {
        $stmt = $this->db->prepare("
            DELETE FROM activity_logs
            WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
}

// Предопределенные действия
class ActivityActions
{
    /**
     * User login constant
     *
     * @const
     */
    public const USER_LOGIN = 'user.login';

    /**
     * User logout constant
     *
     * @const
     */
    public const USER_LOGOUT = 'user.logout';

    /**
     * User register constant
     *
     * @const
     */
    public const USER_REGISTER = 'user.register';

    /**
     * User update profile constant
     *
     * @const
     */
    public const USER_UPDATE_PROFILE = 'user.update_profile';

    /**
     * User change password constant
     *
     * @const
     */
    public const USER_CHANGE_PASSWORD = 'user.change_password';

    /**
     * User password reset request constant
     *
     * @const
     */
    public const USER_PASSWORD_RESET_REQUEST = 'user.password_reset_request';

    /**
     * User password reset constant
     *
     * @const
     */
    public const USER_PASSWORD_RESET = 'user.password_reset';

    /**
     * Admin user create constant
     *
     * @const
     */
    public const ADMIN_USER_CREATE = 'admin.user.create';

    /**
     * Admin user edit constant
     *
     * @const
     */
    public const ADMIN_USER_EDIT = 'admin.user.edit';

    /**
     * Admin user delete constant
     *
     * @const
     */
    public const ADMIN_USER_DELETE = 'admin.user.delete';

    /**
     * Admin user ban constant
     *
     * @const
     */
    public const ADMIN_USER_BAN = 'admin.user.ban';

    /**
     * Email send constant
     *
     * @const
     */
    public const EMAIL_SEND = 'email.send';

    /**
     * Email template create constant
     *
     * @const
     */
    public const EMAIL_TEMPLATE_CREATE = 'email.template.create';

    /**
     * Email template edit constant
     *
     * @const
     */
    public const EMAIL_TEMPLATE_EDIT = 'email.template.edit';

    /**
     * Settings update constant
     *
     * @const
     */
    public const SETTINGS_UPDATE = 'settings.update';

    /**
     * Role assign constant
     *
     * @const
     */
    public const ROLE_ASSIGN = 'role.assign';

    /**
     * Role remove constant
     *
     * @const
     */
    public const ROLE_REMOVE = 'role.remove';
    }
}

namespace {
    /**
     * Global helper function for logging activity
     *
     * @param string $action Action type
     * @param string $description Description
     * @param string|null $entityType Entity type
     * @param int|null $entityId Entity ID
     *
     * @return bool True on success, false on failure
     */
    function logActivity($action, $description = '', $entityType = null, $entityId = null)
    {
        $logger = new \AuraUI\Helpers\ActivityLogger();
        return $logger->log($action, $description, $entityType, $entityId);
    }
}
