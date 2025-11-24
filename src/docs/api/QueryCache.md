# QueryCache

**Файл**: `/var/www/html/core/QueryCache.php`

**Категория**: Core

## Описание

QueryCache - кеширование SQL запросов

## Методы

### `__construct($db = null)`

---

### `query($sql, $params = [], $ttl = null)`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием

---

### `queryOne($sql, $params = [], $ttl = null)`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием
    /
    public function query($sql, $params = [], $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        
        // Генерируем ключ кеша
        $cacheKey = $this->getCacheKey($sql, $params);
        
        // Пытаемся получить из кеша
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Logger::debug('Query cache HIT', ['key' => $cacheKey]);
            return $cached;
        }
        
        // Выполняем запрос
        Logger::debug('Query cache MISS', ['key' => $cacheKey]);
        $startTime = microtime(true);
        
        try {
            if (empty($params)) {
                $result = $this->db->query($sql)->fetchAll();
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll();
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Сохраняем в кеш
            Cache::set($cacheKey, $result, $ttl);
            
            Logger::debug('Query executed and cached', [
                'time' => round($executionTime1000, 2) . 'ms',
                'rows' => count($result)
            ]);
            
            return $result;
            
        } catch (PDOException $e) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
    Выполнить запрос и получить одну строку

---

### `queryValue($sql, $params = [], $ttl = null)`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием
    /
    public function query($sql, $params = [], $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        
        // Генерируем ключ кеша
        $cacheKey = $this->getCacheKey($sql, $params);
        
        // Пытаемся получить из кеша
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Logger::debug('Query cache HIT', ['key' => $cacheKey]);
            return $cached;
        }
        
        // Выполняем запрос
        Logger::debug('Query cache MISS', ['key' => $cacheKey]);
        $startTime = microtime(true);
        
        try {
            if (empty($params)) {
                $result = $this->db->query($sql)->fetchAll();
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll();
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Сохраняем в кеш
            Cache::set($cacheKey, $result, $ttl);
            
            Logger::debug('Query executed and cached', [
                'time' => round($executionTime1000, 2) . 'ms',
                'rows' => count($result)
            ]);
            
            return $result;
            
        } catch (PDOException $e) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
    Выполнить запрос и получить одну строку
    /
    public function queryOne($sql, $params = [], $ttl = null) {
        $result = $this->query($sql, $params, $ttl);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
    Выполнить запрос и получить одно значение

---

### `invalidate($table)`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием
    /
    public function query($sql, $params = [], $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        
        // Генерируем ключ кеша
        $cacheKey = $this->getCacheKey($sql, $params);
        
        // Пытаемся получить из кеша
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Logger::debug('Query cache HIT', ['key' => $cacheKey]);
            return $cached;
        }
        
        // Выполняем запрос
        Logger::debug('Query cache MISS', ['key' => $cacheKey]);
        $startTime = microtime(true);
        
        try {
            if (empty($params)) {
                $result = $this->db->query($sql)->fetchAll();
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll();
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Сохраняем в кеш
            Cache::set($cacheKey, $result, $ttl);
            
            Logger::debug('Query executed and cached', [
                'time' => round($executionTime1000, 2) . 'ms',
                'rows' => count($result)
            ]);
            
            return $result;
            
        } catch (PDOException $e) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
    Выполнить запрос и получить одну строку
    /
    public function queryOne($sql, $params = [], $ttl = null) {
        $result = $this->query($sql, $params, $ttl);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
    Выполнить запрос и получить одно значение
    /
    public function queryValue($sql, $params = [], $ttl = null) {
        $result = $this->queryOne($sql, $params, $ttl);
        return $result ? reset($result) : null;
    }
    
    /**
    Инвалидировать кеш для таблицы

---

### `invalidateAll()`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием
    /
    public function query($sql, $params = [], $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        
        // Генерируем ключ кеша
        $cacheKey = $this->getCacheKey($sql, $params);
        
        // Пытаемся получить из кеша
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Logger::debug('Query cache HIT', ['key' => $cacheKey]);
            return $cached;
        }
        
        // Выполняем запрос
        Logger::debug('Query cache MISS', ['key' => $cacheKey]);
        $startTime = microtime(true);
        
        try {
            if (empty($params)) {
                $result = $this->db->query($sql)->fetchAll();
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll();
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Сохраняем в кеш
            Cache::set($cacheKey, $result, $ttl);
            
            Logger::debug('Query executed and cached', [
                'time' => round($executionTime1000, 2) . 'ms',
                'rows' => count($result)
            ]);
            
            return $result;
            
        } catch (PDOException $e) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
    Выполнить запрос и получить одну строку
    /
    public function queryOne($sql, $params = [], $ttl = null) {
        $result = $this->query($sql, $params, $ttl);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
    Выполнить запрос и получить одно значение
    /
    public function queryValue($sql, $params = [], $ttl = null) {
        $result = $this->queryOne($sql, $params, $ttl);
        return $result ? reset($result) : null;
    }
    
    /**
    Инвалидировать кеш для таблицы
    /
    public function invalidate($table) {
        $pattern = "query:*:{$table}:*";
        Cache::deletePattern($pattern);
        Logger::info('Query cache invalidated', ['table' => $table]);
    }
    
    /**
    Инвалидировать весь кеш запросов

---

### `getCacheKey($sql, $params)`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием
    /
    public function query($sql, $params = [], $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        
        // Генерируем ключ кеша
        $cacheKey = $this->getCacheKey($sql, $params);
        
        // Пытаемся получить из кеша
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Logger::debug('Query cache HIT', ['key' => $cacheKey]);
            return $cached;
        }
        
        // Выполняем запрос
        Logger::debug('Query cache MISS', ['key' => $cacheKey]);
        $startTime = microtime(true);
        
        try {
            if (empty($params)) {
                $result = $this->db->query($sql)->fetchAll();
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll();
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Сохраняем в кеш
            Cache::set($cacheKey, $result, $ttl);
            
            Logger::debug('Query executed and cached', [
                'time' => round($executionTime1000, 2) . 'ms',
                'rows' => count($result)
            ]);
            
            return $result;
            
        } catch (PDOException $e) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
    Выполнить запрос и получить одну строку
    /
    public function queryOne($sql, $params = [], $ttl = null) {
        $result = $this->query($sql, $params, $ttl);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
    Выполнить запрос и получить одно значение
    /
    public function queryValue($sql, $params = [], $ttl = null) {
        $result = $this->queryOne($sql, $params, $ttl);
        return $result ? reset($result) : null;
    }
    
    /**
    Инвалидировать кеш для таблицы
    /
    public function invalidate($table) {
        $pattern = "query:*:{$table}:*";
        Cache::deletePattern($pattern);
        Logger::info('Query cache invalidated', ['table' => $table]);
    }
    
    /**
    Инвалидировать весь кеш запросов
    /
    public function invalidateAll() {
        Cache::deletePattern('query:*');
        Logger::info('All query cache invalidated');
    }
    
    /**
    Генерировать ключ кеша

---

### `extractTableName($sql)`

QueryCache - кеширование SQL запросов
/
class QueryCache {
    private $db;
    private $defaultTTL = 300; // 5 минут по умолчанию
    
    public function __construct($db = null) {
        $this->db = $db ?? getDB();
    }
    
    /**
    Выполнить SELECT запрос с кешированием
    /
    public function query($sql, $params = [], $ttl = null) {
        $ttl = $ttl ?? $this->defaultTTL;
        
        // Генерируем ключ кеша
        $cacheKey = $this->getCacheKey($sql, $params);
        
        // Пытаемся получить из кеша
        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            Logger::debug('Query cache HIT', ['key' => $cacheKey]);
            return $cached;
        }
        
        // Выполняем запрос
        Logger::debug('Query cache MISS', ['key' => $cacheKey]);
        $startTime = microtime(true);
        
        try {
            if (empty($params)) {
                $result = $this->db->query($sql)->fetchAll();
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
                $result = $stmt->fetchAll();
            }
            
            $executionTime = microtime(true) - $startTime;
            
            // Сохраняем в кеш
            Cache::set($cacheKey, $result, $ttl);
            
            Logger::debug('Query executed and cached', [
                'time' => round($executionTime1000, 2) . 'ms',
                'rows' => count($result)
            ]);
            
            return $result;
            
        } catch (PDOException $e) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
    Выполнить запрос и получить одну строку
    /
    public function queryOne($sql, $params = [], $ttl = null) {
        $result = $this->query($sql, $params, $ttl);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
    Выполнить запрос и получить одно значение
    /
    public function queryValue($sql, $params = [], $ttl = null) {
        $result = $this->queryOne($sql, $params, $ttl);
        return $result ? reset($result) : null;
    }
    
    /**
    Инвалидировать кеш для таблицы
    /
    public function invalidate($table) {
        $pattern = "query:*:{$table}:*";
        Cache::deletePattern($pattern);
        Logger::info('Query cache invalidated', ['table' => $table]);
    }
    
    /**
    Инвалидировать весь кеш запросов
    /
    public function invalidateAll() {
        Cache::deletePattern('query:*');
        Logger::info('All query cache invalidated');
    }
    
    /**
    Генерировать ключ кеша
    /
    private function getCacheKey($sql, $params) {
        // Извлекаем имя таблицы из SQL
        $table = $this->extractTableName($sql);
        
        // Генерируем хеш от SQL и параметров
        $hash = md5($sql . serialize($params));
        
        return "query:{$hash}:{$table}:" . substr($hash, 0, 8);
    }
    
    /**
    Извлечь имя таблицы из SQL

---

