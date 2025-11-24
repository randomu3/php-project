# RateLimiter

**Файл**: `/var/www/html/core/RateLimiter.php`

**Категория**: Core

## Описание

Rate Limiter - защита от брутфорса и DDoS

## Методы

### `__construct()`

---

### `createTableIfNotExists()`

---

### `check($action, $maxAttempts = 5, $windowSeconds = 60)`

Rate Limiter - защита от брутфорса и DDoS
/
class RateLimiter {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS rate_limits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                identifier VARCHAR(255) NOT NULL,
                action VARCHAR(100) NOT NULL,
                attempts INT DEFAULT 1,
                reset_at TIMESTAMP NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_limit (identifier, action),
                INDEX idx_reset_at (reset_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    /**
    Проверка лимита
    @param string $action - действие (login, register, email, etc)
    @param int $maxAttempts - максимум попыток
    @param int $windowSeconds - окно времени в секундах
    @return bool - true если можно продолжить, false если лимит превышен

---

### `getRemainingTime($action)`

Rate Limiter - защита от брутфорса и DDoS
/
class RateLimiter {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS rate_limits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                identifier VARCHAR(255) NOT NULL,
                action VARCHAR(100) NOT NULL,
                attempts INT DEFAULT 1,
                reset_at TIMESTAMP NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_limit (identifier, action),
                INDEX idx_reset_at (reset_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    /**
    Проверка лимита
    @param string $action - действие (login, register, email, etc)
    @param int $maxAttempts - максимум попыток
    @param int $windowSeconds - окно времени в секундах
    @return bool - true если можно продолжить, false если лимит превышен
    /
    public function check($action, $maxAttempts = 5, $windowSeconds = 60) {
        $identifier = $this->getIdentifier();
        
        // Очищаем старые записи
        $this->cleanup();
        
        // Получаем текущий лимит
        $stmt = $this->db->prepare("
            SELECT attempts, reset_at 
            FROM rate_limits 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
        $limit = $stmt->fetch();
        
        if (!$limit) {
            // Первая попытка
            $this->record($identifier, $action, $windowSeconds);
            return true;
        }
        
        // Проверяем не истекло ли время
        if (strtotime($limit['reset_at']) < time()) {
            // Время истекло, сбрасываем
            $this->reset($identifier, $action, $windowSeconds);
            return true;
        }
        
        // Проверяем количество попыток
        if ($limit['attempts'] >= $maxAttempts) {
            return false; // Лимит превышен
        }
        
        // Увеличиваем счетчик
        $this->increment($identifier, $action);
        return true;
    }
    
    /**
    Получить оставшееся время блокировки

---

### `clear($action)`

Rate Limiter - защита от брутфорса и DDoS
/
class RateLimiter {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
        $this->createTableIfNotExists();
    }
    
    private function createTableIfNotExists() {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS rate_limits (
                id INT AUTO_INCREMENT PRIMARY KEY,
                identifier VARCHAR(255) NOT NULL,
                action VARCHAR(100) NOT NULL,
                attempts INT DEFAULT 1,
                reset_at TIMESTAMP NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_limit (identifier, action),
                INDEX idx_reset_at (reset_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
    
    /**
    Проверка лимита
    @param string $action - действие (login, register, email, etc)
    @param int $maxAttempts - максимум попыток
    @param int $windowSeconds - окно времени в секундах
    @return bool - true если можно продолжить, false если лимит превышен
    /
    public function check($action, $maxAttempts = 5, $windowSeconds = 60) {
        $identifier = $this->getIdentifier();
        
        // Очищаем старые записи
        $this->cleanup();
        
        // Получаем текущий лимит
        $stmt = $this->db->prepare("
            SELECT attempts, reset_at 
            FROM rate_limits 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
        $limit = $stmt->fetch();
        
        if (!$limit) {
            // Первая попытка
            $this->record($identifier, $action, $windowSeconds);
            return true;
        }
        
        // Проверяем не истекло ли время
        if (strtotime($limit['reset_at']) < time()) {
            // Время истекло, сбрасываем
            $this->reset($identifier, $action, $windowSeconds);
            return true;
        }
        
        // Проверяем количество попыток
        if ($limit['attempts'] >= $maxAttempts) {
            return false; // Лимит превышен
        }
        
        // Увеличиваем счетчик
        $this->increment($identifier, $action);
        return true;
    }
    
    /**
    Получить оставшееся время блокировки
    /
    public function getRemainingTime($action) {
        $identifier = $this->getIdentifier();
        
        $stmt = $this->db->prepare("
            SELECT reset_at 
            FROM rate_limits 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
        $limit = $stmt->fetch();
        
        if (!$limit) {
            return 0;
        }
        
        $remaining = strtotime($limit['reset_at']) - time();
        return max(0, $remaining);
    }
    
    /**
    Сбросить лимит (например, после успешного логина)

---

### `getIdentifier()`

---

### `record($identifier, $action, $windowSeconds)`

---

### `increment($identifier, $action)`

---

### `reset($identifier, $action, $windowSeconds)`

---

### `cleanup()`

---

