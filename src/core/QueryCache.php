<?php

namespace AuraUI\Core;

use PDOException;

/**
 *  Query Cache
 *
 * @package AuraUI\Core
 */
class QueryCache
{
    /**
     * Db
     *
     * @var mixed
     */
    public $db;

    /**
     * DefaultTTL
     *
     * @var mixed
     */
    public $defaultTTL;

    /**
     *   construct
     *
     * @param  $db Parameter
     */
    public function __construct($db = null)
    {
        $this->db = $db ?? getDB();
    }

    /**
     * Query
     *
     * @param mixed $sql Parameter
     * @param mixed $params Parameter
     * @param mixed $ttl Parameter
     */
    public function query(mixed $sql, mixed $params = [], mixed $ttl = null)
    {
        $ttl ??= $this->defaultTTL;

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
                'time' => round($executionTime * 1000, 2) . 'ms',
                'rows' => count($result)
            ]);

            return $result;

        } catch (PDOException $pdoException) {
            Logger::error('Query cache error', [
                'sql' => $sql,
                'error' => $pdoException->getMessage()
            ]);
            throw $pdoException;
        }
    }

    /**
     * Query One
     *
     * @param mixed $sql Parameter
     * @param mixed $params Parameter
     * @param mixed $ttl Parameter
     */
    public function queryOne(mixed $sql, mixed $params = [], mixed $ttl = null)
    {
        $result = $this->query($sql, $params, $ttl);
        return empty($result) ? null : $result[0];
    }

    /**
     * Query Value
     *
     * @param mixed $sql Parameter
     * @param mixed $params Parameter
     * @param mixed $ttl Parameter
     */
    public function queryValue(mixed $sql, mixed $params = [], mixed $ttl = null)
    {
        $result = $this->queryOne($sql, $params, $ttl);
        return $result ? reset($result) : null;
    }

    /**
     * Invalidate
     *
     * @return void
     */
    public function invalidate(): void
    {
        $pattern = sprintf('query:*:%s:*', $table);
        Cache::deletePattern($pattern);
        Logger::info('Query cache invalidated', ['table' => $table]);
    }

    /**
     * Invalidate All
     *
     * @return void
     */
    public function invalidateAll(): void
    {
        Cache::deletePattern('query:*');
        Logger::info('All query cache invalidated');
    }

    /**
     * Get Cache Key
     *
     * @param mixed $sql Parameter
     * @param mixed $params Parameter
     *
     * @return string String value
     */
    private function getCacheKey(mixed $sql, mixed $params): string
    {
        // Извлекаем имя таблицы из SQL
        $table = $this->extractTableName($sql);

        // Генерируем хеш от SQL и параметров
        $hash = md5($sql . serialize($params));

        return sprintf('query:%s:%s:', $hash, $table) . substr($hash, 0, 8);
    }

    /**
     * Extract Table Name
     *
     * @param mixed $sql Parameter
     *
     * @return string String value
     */
    private function extractTableName(mixed $sql): string
    {
        // Простое извлечение имени таблицы
        if (preg_match('/FROM\s+`?(\w+)`?/i', $sql, $matches)) {
            return $matches[1];
        }

        if (preg_match('/UPDATE\s+`?(\w+)`?/i', $sql, $matches)) {
            return $matches[1];
        }

        if (preg_match('/INSERT\s+INTO\s+`?(\w+)`?/i', $sql, $matches)) {
            return $matches[1];
        }

        return 'unknown';
    }
}
