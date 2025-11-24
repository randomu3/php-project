<?php

namespace AuraUI\Core;

use Exception;
use Redis;

/**
 *  Cache
 *
 * @package AuraUI\Core
 */
class Cache
{
    /**
     * Redis
     *
     * @var ?\Redis
     */
    private static ?\Redis $redis = null;

    /**
     * Enabled
     *
     * @var bool
     */
    private static bool $enabled = true;

    /**
     * Get Redis
     *
     * @return ?\Redis
     */
    private static function getRedis(): ?\Redis
    {
        if (!self::$redis instanceof \Redis) {
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
     * Get
     *
     * @param mixed $key Cache key
     */
    public static function get(mixed $key)
    {
        if (!self::$enabled) {
            return null;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
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

        } catch (Exception $exception) {
            Logger::error('Cache get error', ['key' => $key, 'error' => $exception->getMessage()]);
            return null;
        }
    }

    /**
     * Set
     *
     * @param mixed $key Cache key
     * @param mixed $value Parameter
     * @param mixed $ttl Parameter
     *
     * @return bool|\Redis|string
     */
    public static function set(mixed $key, mixed $value, mixed $ttl = 3600): bool|\Redis|string
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            // Сериализуем если это не строка
            $serialized = is_string($value) ? $value : serialize($value);

            if ($ttl > 0) {
                return $redis->setex($key, $ttl, $serialized);
            }

            return $redis->set($key, $serialized);

        } catch (Exception $exception) {
            Logger::error('Cache set error', ['key' => $key, 'error' => $exception->getMessage()]);
            return false;
        }
    }

    /**
     * Delete
     *
     * @param mixed $key Cache key
     */
    public static function delete(mixed $key)
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            return $redis->del($key) > 0;
        } catch (Exception $exception) {
            Logger::error('Cache delete error', ['key' => $key, 'error' => $exception->getMessage()]);
            return false;
        }
    }

    /**
     * Delete Pattern
     *
     * @param mixed $pattern Parameter
     */
    public static function deletePattern(mixed $pattern)
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            $keys = $redis->keys($pattern);
            if (!empty($keys)) {
                return $redis->del($keys) > 0;
            }

            return true;
        } catch (Exception $exception) {
            Logger::error('Cache delete pattern error', ['pattern' => $pattern, 'error' => $exception->getMessage()]);
            return false;
        }
    }

    /**
     * Exists
     *
     * @param mixed $key Cache key
     */
    public static function exists(mixed $key)
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            return $redis->exists($key) > 0;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Increment
     *
     * @param mixed $key Cache key
     * @param mixed $by Parameter
     *
     * @return false|int|\Redis
     */
    public static function increment(mixed $key, mixed $by = 1): false|int|\Redis
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            return $redis->incrBy($key, $by);
        } catch (Exception $exception) {
            Logger::error('Cache increment error', ['key' => $key, 'error' => $exception->getMessage()]);
            return false;
        }
    }

    /**
     * Decrement
     *
     * @param mixed $key Cache key
     * @param mixed $by Parameter
     *
     * @return false|int|\Redis
     */
    public static function decrement(mixed $key, mixed $by = 1): false|int|\Redis
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            return $redis->decrBy($key, $by);
        } catch (Exception $exception) {
            Logger::error('Cache decrement error', ['key' => $key, 'error' => $exception->getMessage()]);
            return false;
        }
    }

    /**
     * Remember
     *
     * @param mixed $key Cache key
     * @param mixed $ttl Parameter
     * @param mixed $callback Parameter
     */
    public static function remember(mixed $key, mixed $ttl, mixed $callback)
    {
        $value = self::get($key);

        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        self::set($key, $value, $ttl);

        return $value;
    }

    /**
     * Flush
     *
     * @return bool|\Redis
     */
    public static function flush(): bool|\Redis
    {
        if (!self::$enabled) {
            return false;
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return false;
        }

        try {
            return $redis->flushDB();
        } catch (Exception $exception) {
            Logger::error('Cache flush error', ['error' => $exception->getMessage()]);
            return false;
        }
    }

    /**
     * Stats
     *
     * @return array Data array
     */
    public static function stats(): array
    {
        if (!self::$enabled) {
            return ['enabled' => false];
        }

        $redis = self::getRedis();
        if (!$redis instanceof \Redis) {
            return ['enabled' => false];
        }

        try {
            $info = $redis->info();
            return [
                'enabled' => true,
                'used_memory' => $info['used_memory_human'] ?? 'N/A',
                'keys' => $redis->dbSize(),
                'hits' => $info['keyspace_hits'] ?? 0,
                'misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => isset($info['keyspace_hits'], $info['keyspace_misses'])
                    ? round($info['keyspace_hits'] / ($info['keyspace_hits'] + $info['keyspace_misses']) * 100, 2) . '%'
                    : 'N/A'
            ];
        } catch (Exception $exception) {
            return ['enabled' => false, 'error' => $exception->getMessage()];
        }
    }
}
