<?php
require_once __DIR__ . '/../config.php';

class ActivityLogger {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Записать действие в лог
     */
    public function log($action, $description = '', $entityType = null, $entityId = null, $userId = null) {
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
        } catch (PDOException $e) {
            // Логирование не должно ломать основной функционал
            error_log("ActivityLogger error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Получить логи пользователя
     */
    public function getUserLogs($userId, $limit = 50, $offset = 0) {
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
     * Получить все логи
     */
    public function getAllLogs($limit = 100, $offset = 0, $filters = []) {
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
        
        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";
        
        $stmt = $this->db->prepare("
            SELECT al.*, u.username
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            $whereClause
            ORDER BY al.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Получить статистику действий
     */
    public function getStats($days = 7) {
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
     * Удалить старые логи
     */
    public function cleanOldLogs($days = 90) {
        $stmt = $this->db->prepare("
            DELETE FROM activity_logs
            WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");
        return $stmt->execute([$days]);
    }
}

// Глобальная функция для удобства
function logActivity($action, $description = '', $entityType = null, $entityId = null) {
    $logger = new ActivityLogger();
    return $logger->log($action, $description, $entityType, $entityId);
}

// Предопределенные действия
class ActivityActions {
    const USER_LOGIN = 'user.login';
    const USER_LOGOUT = 'user.logout';
    const USER_REGISTER = 'user.register';
    const USER_UPDATE_PROFILE = 'user.update_profile';
    const USER_CHANGE_PASSWORD = 'user.change_password';
    const USER_PASSWORD_RESET_REQUEST = 'user.password_reset_request';
    const USER_PASSWORD_RESET = 'user.password_reset';
    
    const ADMIN_USER_CREATE = 'admin.user.create';
    const ADMIN_USER_EDIT = 'admin.user.edit';
    const ADMIN_USER_DELETE = 'admin.user.delete';
    const ADMIN_USER_BAN = 'admin.user.ban';
    
    const EMAIL_SEND = 'email.send';
    const EMAIL_TEMPLATE_CREATE = 'email.template.create';
    const EMAIL_TEMPLATE_EDIT = 'email.template.edit';
    
    const SETTINGS_UPDATE = 'settings.update';
    
    const ROLE_ASSIGN = 'role.assign';
    const ROLE_REMOVE = 'role.remove';
}
