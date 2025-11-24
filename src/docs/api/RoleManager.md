# RoleManager

**Файл**: `/var/www/html/helpers/RoleManager.php`

**Категория**: Helpers

## Описание

Проверить, есть ли у пользователя роль

## Методы

### `__construct()`

---

### `hasRole($userId, $roleName)`

Проверить, есть ли у пользователя роль

---

### `hasPermission($userId, $permissionName)`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право

---

### `getUserRoles($userId)`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя

---

### `getUserPermissions($userId)`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя
    /
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
    Получить все права пользователя

---

### `assignRole($userId, $roleId)`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя
    /
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
    Получить все права пользователя
    /
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
    Назначить роль пользователю

---

### `removeRole($userId, $roleId)`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя
    /
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
    Получить все права пользователя
    /
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
    Назначить роль пользователю
    /
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
    Удалить роль у пользователя

---

### `getAllRoles()`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя
    /
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
    Получить все права пользователя
    /
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
    Назначить роль пользователю
    /
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
    Удалить роль у пользователя
    /
    public function removeRole($userId, $roleId) {
        $stmt = $this->db->prepare("
            DELETE FROM user_roles
            WHERE user_id = ? AND role_id = ?
        ");
        return $stmt->execute([$userId, $roleId]);
    }
    
    /**
    Получить все роли

---

### `getAllPermissions()`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя
    /
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
    Получить все права пользователя
    /
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
    Назначить роль пользователю
    /
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
    Удалить роль у пользователя
    /
    public function removeRole($userId, $roleId) {
        $stmt = $this->db->prepare("
            DELETE FROM user_roles
            WHERE user_id = ? AND role_id = ?
        ");
        return $stmt->execute([$userId, $roleId]);
    }
    
    /**
    Получить все роли
    /
    public function getAllRoles() {
        $stmt = $this->db->query("SELECTFROM roles ORDER BY name");
        return $stmt->fetchAll();
    }
    
    /**
    Получить все права

---

### `getRolePermissions($roleId)`

Проверить, есть ли у пользователя роль
    /
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
    Проверить, есть ли у пользователя право
    /
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
    Получить все роли пользователя
    /
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
    Получить все права пользователя
    /
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
    Назначить роль пользователю
    /
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
    Удалить роль у пользователя
    /
    public function removeRole($userId, $roleId) {
        $stmt = $this->db->prepare("
            DELETE FROM user_roles
            WHERE user_id = ? AND role_id = ?
        ");
        return $stmt->execute([$userId, $roleId]);
    }
    
    /**
    Получить все роли
    /
    public function getAllRoles() {
        $stmt = $this->db->query("SELECTFROM roles ORDER BY name");
        return $stmt->fetchAll();
    }
    
    /**
    Получить все права
    /
    public function getAllPermissions() {
        $stmt = $this->db->query("SELECTFROM permissions ORDER BY category, name");
        return $stmt->fetchAll();
    }
    
    /**
    Получить права роли

---

