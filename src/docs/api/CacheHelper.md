# CacheHelper

**Файл**: `/var/www/html/helpers/CacheHelper.php`

**Категория**: Helpers

## Описание

CacheHelper - Утилиты для управления кешированием

## Методы

### `generateETag($content)`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента

---

### `checkETag($etag)`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось

---

### `setCacheHeaders($maxAge = 3600)`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось
    /
    public static function checkETag($etag) {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        
        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header("ETag: $etag");
            exit;
        }
        
        header("ETag: $etag");
    }
    
    /**
    Устанавливает заголовки кеширования для статики

---

### `setDynamicHeaders($maxAge = 600)`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось
    /
    public static function checkETag($etag) {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        
        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header("ETag: $etag");
            exit;
        }
        
        header("ETag: $etag");
    }
    
    /**
    Устанавливает заголовки кеширования для статики
    /
    public static function setCacheHeaders($maxAge = 3600) {
        header("Cache-Control: public, max-age=$maxAge");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Устанавливает заголовки для динамического контента

---

### `noCache()`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось
    /
    public static function checkETag($etag) {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        
        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header("ETag: $etag");
            exit;
        }
        
        header("ETag: $etag");
    }
    
    /**
    Устанавливает заголовки кеширования для статики
    /
    public static function setCacheHeaders($maxAge = 3600) {
        header("Cache-Control: public, max-age=$maxAge");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Устанавливает заголовки для динамического контента
    /
    public static function setDynamicHeaders($maxAge = 600) {
        header("Cache-Control: public, max-age=$maxAge, must-revalidate");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Запрещает кеширование (для приватных данных)

---

### `clearOPcache()`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось
    /
    public static function checkETag($etag) {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        
        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header("ETag: $etag");
            exit;
        }
        
        header("ETag: $etag");
    }
    
    /**
    Устанавливает заголовки кеширования для статики
    /
    public static function setCacheHeaders($maxAge = 3600) {
        header("Cache-Control: public, max-age=$maxAge");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Устанавливает заголовки для динамического контента
    /
    public static function setDynamicHeaders($maxAge = 600) {
        header("Cache-Control: public, max-age=$maxAge, must-revalidate");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Запрещает кеширование (для приватных данных)
    /
    public static function noCache() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
    
    /**
    Очищает OPcache (для админов)

---

### `getOPcacheStats()`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось
    /
    public static function checkETag($etag) {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        
        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header("ETag: $etag");
            exit;
        }
        
        header("ETag: $etag");
    }
    
    /**
    Устанавливает заголовки кеширования для статики
    /
    public static function setCacheHeaders($maxAge = 3600) {
        header("Cache-Control: public, max-age=$maxAge");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Устанавливает заголовки для динамического контента
    /
    public static function setDynamicHeaders($maxAge = 600) {
        header("Cache-Control: public, max-age=$maxAge, must-revalidate");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Запрещает кеширование (для приватных данных)
    /
    public static function noCache() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
    
    /**
    Очищает OPcache (для админов)
    /
    public static function clearOPcache() {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }
        return false;
    }
    
    /**
    Получает статистику OPcache

---

### `asset($path, $version = null)`

CacheHelper - Утилиты для управления кешированием
/
class CacheHelper {
    
    /**
    Генерирует ETag для контента
    /
    public static function generateETag($content) {
        return md5($content);
    }
    
    /**
    Проверяет ETag и отправляет 304 если не изменилось
    /
    public static function checkETag($etag) {
        $clientETag = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        
        if ($clientETag === $etag) {
            header('HTTP/1.1 304 Not Modified');
            header("ETag: $etag");
            exit;
        }
        
        header("ETag: $etag");
    }
    
    /**
    Устанавливает заголовки кеширования для статики
    /
    public static function setCacheHeaders($maxAge = 3600) {
        header("Cache-Control: public, max-age=$maxAge");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Устанавливает заголовки для динамического контента
    /
    public static function setDynamicHeaders($maxAge = 600) {
        header("Cache-Control: public, max-age=$maxAge, must-revalidate");
        header("Expires: " . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
    }
    
    /**
    Запрещает кеширование (для приватных данных)
    /
    public static function noCache() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
    
    /**
    Очищает OPcache (для админов)
    /
    public static function clearOPcache() {
        if (function_exists('opcache_reset')) {
            return opcache_reset();
        }
        return false;
    }
    
    /**
    Получает статистику OPcache
    /
    public static function getOPcacheStats() {
        if (function_exists('opcache_get_status')) {
            return opcache_get_status(false);
        }
        return null;
    }
    
    /**
    Генерирует версионированный URL для статики

---

