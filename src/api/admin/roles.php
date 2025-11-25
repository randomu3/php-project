<?php
/**
 * Admin Roles API
 * 
 * RBAC management - roles and permissions
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
$db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

switch ($action) {
    case 'get_roles':
        getRoles($db);
        break;
    case 'get_role':
        getRole($db, (int)($_GET['id'] ?? 0));
        break;
    case 'create_role':
        createRole($db);
        break;
    case 'update_role':
        updateRole($db);
        break;
    case 'delete_role':
        deleteRole($db, (int)($_POST['id'] ?? 0));
        break;
    case 'get_permissions':
        getPermissions($db);
        break;
    case 'get_role_permissions':
        getRolePermissions($db, (int)($_GET['role_id'] ?? 0));
        break;
    case 'update_role_permissions':
        updateRolePermissions($db);
        break;
    case 'get_user_roles':
        getUserRoles($db, (int)($_GET['user_id'] ?? 0));
        break;
    case 'assign_user_role':
        assignUserRole($db);
        break;
    case 'remove_user_role':
        removeUserRole($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * Get all roles
 */
function getRoles($db): void
{
    $stmt = $db->query("
        SELECT r.*, 
               (SELECT COUNT(*) FROM user_roles WHERE role_id = r.id) as users_count,
               (SELECT COUNT(*) FROM role_permissions WHERE role_id = r.id) as permissions_count
        FROM roles r ORDER BY r.id
    ");
    echo json_encode(['success' => true, 'roles' => $stmt->fetchAll()]);
}

/**
 * Get single role
 */
function getRole($db, int $id): void
{
    $stmt = $db->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    $role = $stmt->fetch();
    
    if (!$role) {
        echo json_encode(['success' => false, 'error' => 'Role not found']);
        return;
    }
    
    echo json_encode(['success' => true, 'role' => $role]);
}

/**
 * Create new role
 */
function createRole($db): void
{
    $name = trim($_POST['name'] ?? '');
    $displayName = trim($_POST['display_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (empty($name) || empty($displayName)) {
        echo json_encode(['success' => false, 'error' => 'Name and display name required']);
        return;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO roles (name, display_name, description) VALUES (?, ?, ?)");
        $stmt->execute([$name, $displayName, $description]);
        echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Role already exists']);
    }
}

/**
 * Update role
 */
function updateRole($db): void
{
    $id = (int)($_POST['id'] ?? 0);
    $displayName = trim($_POST['display_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid role ID']);
        return;
    }
    
    $stmt = $db->prepare("UPDATE roles SET display_name = ?, description = ? WHERE id = ?");
    $stmt->execute([$displayName, $description, $id]);
    echo json_encode(['success' => true]);
}

/**
 * Delete role
 */
function deleteRole($db, int $id): void
{
    if ($id <= 3) {
        echo json_encode(['success' => false, 'error' => 'Cannot delete system roles']);
        return;
    }
    
    $stmt = $db->prepare("DELETE FROM roles WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Get all permissions grouped by category
 */
function getPermissions($db): void
{
    $stmt = $db->query("SELECT * FROM permissions ORDER BY category, name");
    $permissions = $stmt->fetchAll();
    
    $grouped = [];
    foreach ($permissions as $perm) {
        $grouped[$perm['category']][] = $perm;
    }
    
    echo json_encode(['success' => true, 'permissions' => $permissions, 'grouped' => $grouped]);
}

/**
 * Get permissions for a role
 */
function getRolePermissions($db, int $roleId): void
{
    $stmt = $db->prepare("SELECT permission_id FROM role_permissions WHERE role_id = ?");
    $stmt->execute([$roleId]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['success' => true, 'permission_ids' => $ids]);
}

/**
 * Update role permissions
 */
function updateRolePermissions($db): void
{
    $roleId = (int)($_POST['role_id'] ?? 0);
    $permissionIds = json_decode($_POST['permission_ids'] ?? '[]', true);
    
    if ($roleId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid role ID']);
        return;
    }
    
    $db->beginTransaction();
    try {
        $db->prepare("DELETE FROM role_permissions WHERE role_id = ?")->execute([$roleId]);
        
        $stmt = $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
        foreach ($permissionIds as $permId) {
            $stmt->execute([$roleId, (int)$permId]);
        }
        
        $db->commit();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $db->rollBack();
        echo json_encode(['success' => false, 'error' => 'Failed to update permissions']);
    }
}

/**
 * Get user roles
 */
function getUserRoles($db, int $userId): void
{
    $stmt = $db->prepare("
        SELECT r.* FROM roles r
        JOIN user_roles ur ON r.id = ur.role_id
        WHERE ur.user_id = ?
    ");
    $stmt->execute([$userId]);
    echo json_encode(['success' => true, 'roles' => $stmt->fetchAll()]);
}

/**
 * Assign role to user
 */
function assignUserRole($db): void
{
    $userId = (int)($_POST['user_id'] ?? 0);
    $roleId = (int)($_POST['role_id'] ?? 0);
    
    try {
        $stmt = $db->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)");
        $stmt->execute([$userId, $roleId]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to assign role']);
    }
}

/**
 * Remove role from user
 */
function removeUserRole($db): void
{
    $userId = (int)($_POST['user_id'] ?? 0);
    $roleId = (int)($_POST['role_id'] ?? 0);
    
    $stmt = $db->prepare("DELETE FROM user_roles WHERE user_id = ? AND role_id = ?");
    $stmt->execute([$userId, $roleId]);
    echo json_encode(['success' => true]);
}
