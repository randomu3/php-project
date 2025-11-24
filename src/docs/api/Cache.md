# Cache

**Файл**: `/var/www/html/core/Cache.php`

**Категория**: Core

## Описание

Cache - Redis кеширование для максимальной производительности

## Методы

### `getRedis()`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение

---

### `get($key)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение

---

### `set($key, $value, $ttl = 3600)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш

---

### `delete($key)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша

---

### `deletePattern($pattern)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну

---

### `exists($key)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну
    /
    public static function deletePattern($pattern) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }
            return true;
        } catch (Exception $e) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Проверить существование ключа

---

### `increment($key, $by = 1)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну
    /
    public static function deletePattern($pattern) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }
            return true;
        } catch (Exception $e) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Проверить существование ключа
    /
    public static function exists($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->exists($key) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
    Увеличить счетчик

---

### `decrement($key, $by = 1)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну
    /
    public static function deletePattern($pattern) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }
            return true;
        } catch (Exception $e) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Проверить существование ключа
    /
    public static function exists($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->exists($key) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
    Увеличить счетчик
    /
    public static function increment($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->incrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache increment error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Уменьшить счетчик

---

### `remember($key, $ttl, $callback)`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну
    /
    public static function deletePattern($pattern) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }
            return true;
        } catch (Exception $e) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Проверить существование ключа
    /
    public static function exists($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->exists($key) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
    Увеличить счетчик
    /
    public static function increment($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->incrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache increment error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Уменьшить счетчик
    /
    public static function decrement($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->decrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache decrement error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Получить или установить (если не существует)

---

### `flush()`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну
    /
    public static function deletePattern($pattern) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }
            return true;
        } catch (Exception $e) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Проверить существование ключа
    /
    public static function exists($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->exists($key) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
    Увеличить счетчик
    /
    public static function increment($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->incrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache increment error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Уменьшить счетчик
    /
    public static function decrement($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->decrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache decrement error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Получить или установить (если не существует)
    /
    public static function remember($key, $ttl, $callback) {
        $value = self::get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        self::set($key, $value, $ttl);
        
        return $value;
    }
    
    /**
    Очистить весь кеш

---

### `stats()`

Cache - Redis кеширование для максимальной производительности
/
class Cache {
    private static $redis = null;
    private static $enabled = true;
    
    /**
    Получить Redis соединение
    /
    private static function getRedis() {
        if (self::$redis === null) {
            try {
                self::$redis = new Redis();
                $host = getenv('REDIS_HOST') ?: 'redis';
                $port = getenv('REDIS_PORT') ?: 6379;
                
                if (!self::$redis->connect($host, $port, 2)) {
                    self::$enabled = false;
                    Logger::warning('Redis connection failed', ['host' => $host, 'port' => $port]);
                    return null;
                }
                
                // Проверяем соединение
                self::$redis->ping();
                
            } catch (Exception $e) {
                self::$enabled = false;
                Logger::error('Redis error', ['message' => $e->getMessage()]);
                return null;
            }
        }
        
        return self::$redis;
    }
    
    /**
    Получить значение из кеша
    /
    public static function get($key) {
        if (!self::$enabled) {
            return null;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return null;
        }
        
        try {
            $value = $redis->get($key);
            
            if ($value === false) {
                return null;
            }
            
            // Десериализуем если это не строка
            $unserialized = @unserialize($value);
            return $unserialized !== false ? $unserialized : $value;
            
        } catch (Exception $e) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
    Сохранить значение в кеш
    /
    public static function set($key, $value, $ttl = 3600) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);
            
            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            } else {
                return $redis->set($key, $serialized);
            }
            
        } catch (Exception $e) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить значение из кеша
    /
    public static function delete($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->del($key) > 0;
        } catch (Exception $e) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Удалить все ключи по паттерну
    /
    public static function deletePattern($pattern) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }
            return true;
        } catch (Exception $e) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Проверить существование ключа
    /
    public static function exists($key) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->exists($key) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
    Увеличить счетчик
    /
    public static function increment($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->incrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache increment error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Уменьшить счетчик
    /
    public static function decrement($key, $by = 1) {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->decrBy($key, $by);
        } catch (Exception $e) {
            Logger::error('Cache decrement error', ['key' => $key, 'error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Получить или установить (если не существует)
    /
    public static function remember($key, $ttl, $callback) {
        $value = self::get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        self::set($key, $value, $ttl);
        
        return $value;
    }
    
    /**
    Очистить весь кеш
    /
    public static function flush() {
        if (!self::$enabled) {
            return false;
        }
        
        $redis = self::getRedis();
        if (!$redis) {
            return false;
        }
        
        try {
            return $redis->flushDB();
        } catch (Exception $e) {
            Logger::error('Cache flush error', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
    Получить статистику кеша

---

