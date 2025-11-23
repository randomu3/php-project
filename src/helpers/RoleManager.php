<?php
require_once __DIR__ . '/../config.php';

class RoleManager {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Проверить, есть ли у пользователя роль
     */
    public function hasRole($userId, $roleName) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM user_roles ur
            JOIN roles r ON ur.role_id = r.id
            WHERE ur.user_id = ? AND r.name = ?
        ");
        $stmt->execute([$userId, $roleName]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Проверить, есть ли у пользователя право
     */
    public function hasPermission($userId, $permissionName) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM user_roles ur
            JOIN role_permissions rp ON ur.role_id = rp.role_id
            JOIN permissions p ON rp.permission_id = p.id
            WHERE ur.user_id = ? AND p.name = ?
        ");
        $stmt->execute([$userId, $permissionName]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Получить все роли пользователя
     */
    public function getUserRoles($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*
            FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Получить все права пользователя
     */
    public function getUserPermissions($userId) {
        $stmt = $this->db->prepare("
            SELECT DISTINCT p.*
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            JOIN user_roles ur ON rp.role_id = ur.role_id
            WHERE ur.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Назначить роль пользователю
     */
    public function assignRole($userId, $roleId) {
        try {
            $stmt = $this->db->prepare("
                INSERT IGNORE INTO user_roles (user_id, role_id)
                VALUES (?, ?)
            ");
            return $stmt->execute([$userId, $roleId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Удалить роль у пользователя
     */
    public function removeRole($userId, $roleId) {
        $stmt = $this->db->prepare("
            DELETE FROM user_roles
            WHERE user_id = ? AND role_id = ?
        ");
        return $stmt->execute([$userId, $roleId]);
    }
    
    /**
     * Получить все роли
     */
    public function getAllRoles() {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY name");
        return $stmt->fetchAll();
    }
    
    /**
     * Получить все права
     */
    public function getAllPermissions() {
        $stmt = $this->db->query("SELECT * FROM permissions ORDER BY category, name");
        return $stmt->fetchAll();
    }
    
    /**
     * Получить права роли
     */
    public function getRolePermissions($roleId) {
        $stmt = $this->db->prepare("
            SELECT p.*
            FROM permissions p
            JOIN role_permissions rp ON p.id = rp.permission_id
            WHERE rp.role_id = ?
        ");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll();
    }
}

// Глобальные функции для удобства
function hasRole($roleName) {
    if (!isLoggedIn()) return false;
    $rm = new RoleManager();
    return $rm->hasRole($_SESSION['user_id'], $roleName);
}

function hasPermission($permissionName) {
    if (!isLoggedIn()) return false;
    $rm = new RoleManager();
    return $rm->hasPermission($_SESSION['user_id'], $permissionName);
}

function requirePermission($permissionName) {
    if (!hasPermission($permissionName)) {
        http_response_code(403);
        die('Доступ запрещен. У вас нет необходимых прав.');
    }
}
