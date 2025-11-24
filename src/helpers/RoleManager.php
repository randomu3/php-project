<?php

namespace AuraUI\Helpers;

use PDOException;

/**
 *  Role Manager
 *
 * @package AuraUI\Helpers
 */
class RoleManager
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
     * Has Role
     *
     * @return bool True on success, false on failure
     */
    public function hasRole(): bool
    {
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
     * Has Permission
     *
     * @return bool True on success, false on failure
     */
    public function hasPermission(): bool
    {
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
     * Get User Roles
     *
     * @param  $userId User ID
     */
    public function getUserRoles($userId)
    {
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
     * Get User Permissions
     *
     * @param  $userId User ID
     */
    public function getUserPermissions($userId)
    {
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
     * Assign Role
     *
     * @param  $userId User ID
     * @param  $roleId Parameter
     */
    public function assignRole($userId, $roleId)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT IGNORE INTO user_roles (user_id, role_id)
                VALUES (?, ?)
            ");
            return $stmt->execute([$userId, $roleId]);
        } catch (PDOException) {
            return false;
        }
    }

    /**
     * Remove Role
     *
     * @param  $userId User ID
     * @param  $roleId Parameter
     */
    public function removeRole($userId, $roleId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_roles
            WHERE user_id = ? AND role_id = ?
        ");
        return $stmt->execute([$userId, $roleId]);
    }

    /**
     * Get All Roles
     */
    public function getAllRoles()
    {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY name");
        return $stmt->fetchAll();
    }

    /**
     * Get All Permissions
     */
    public function getAllPermissions()
    {
        $stmt = $this->db->query("SELECT * FROM permissions ORDER BY category, name");
        return $stmt->fetchAll();
    }

    /**
     * Get Role Permissions
     *
     * @param  $roleId Parameter
     */
    public function getRolePermissions($roleId)
    {
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
function hasRole($roleName)
{
    if (!isLoggedIn()) {
        return false;
    }

    $rm = new RoleManager();
    return $rm->hasRole();
}

function hasPermission($permissionName)
{
    if (!isLoggedIn()) {
        return false;
    }

    $rm = new RoleManager();
    return $rm->hasPermission();
}

function requirePermission($permissionName): void
{
    if (!hasPermission($permissionName)) {
        http_response_code(403);
        die('Доступ запрещен. У вас нет необходимых прав.');
    }
}
