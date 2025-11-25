<?php

namespace AuraUI\Helpers;

/**
 * System Logger
 * 
 * Logs system events to database
 * 
 * @package AuraUI\Helpers
 */
class SystemLogger
{
    private static ?\PDO $db = null;

    /**
     * Log debug message
     *
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $channel Log channel
     *
     * @return void
     */
    public static function debug(string $message, array $context = [], string $channel = 'app'): void
    {
        self::log('debug', $message, $context, $channel);
    }

    /**
     * Log info message
     *
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $channel Log channel
     *
     * @return void
     */
    public static function info(string $message, array $context = [], string $channel = 'app'): void
    {
        self::log('info', $message, $context, $channel);
    }

    /**
     * Log warning message
     *
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $channel Log channel
     *
     * @return void
     */
    public static function warning(string $message, array $context = [], string $channel = 'app'): void
    {
        self::log('warning', $message, $context, $channel);
    }

    /**
     * Log error message
     *
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $channel Log channel
     *
     * @return void
     */
    public static function error(string $message, array $context = [], string $channel = 'app'): void
    {
        self::log('error', $message, $context, $channel);
    }

    /**
     * Log critical message
     *
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $channel Log channel
     *
     * @return void
     */
    public static function critical(string $message, array $context = [], string $channel = 'app'): void
    {
        self::log('critical', $message, $context, $channel);
    }

    /**
     * Log message to database
     *
     * @param string $level Log level
     * @param string $message Log message
     * @param array $context Additional context
     * @param string $channel Log channel
     *
     * @return void
     */
    private static function log(string $level, string $message, array $context, string $channel): void
    {
        try {
            $db = self::getDB();
            if (!$db) return;

            // Get caller info
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $caller = $trace[2] ?? $trace[1] ?? [];
            
            $stmt = $db->prepare("
                INSERT INTO system_logs (level, channel, message, context, file, line, user_id, ip_address, url)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $level,
                $channel,
                $message,
                !empty($context) ? json_encode($context) : null,
                $caller['file'] ?? null,
                $caller['line'] ?? null,
                $_SESSION['user_id'] ?? null,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['REQUEST_URI'] ?? null
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the app if logging fails
            error_log("SystemLogger error: " . $e->getMessage());
        }
    }

    /**
     * Get database connection
     *
     * @return \PDO|null Database connection
     */
    private static function getDB(): ?\PDO
    {
        if (self::$db === null) {
            try {
                self::$db = getDB();
            } catch (\Exception $e) {
                return null;
            }
        }
        return self::$db;
    }

    /**
     * Log exception
     *
     * @param \Throwable $exception Exception to log
     * @param string $channel Log channel
     *
     * @return void
     */
    public static function exception(\Throwable $exception, string $channel = 'app'): void
    {
        self::log('error', $exception->getMessage(), [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ], $channel);
    }
}
