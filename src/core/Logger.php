<?php

namespace AuraUI\Core;

use SplFileObject;

/**
 *  Logger
 *
 * @package AuraUI\Core
 */
class Logger
{
    /**
     * LogDir
     *
     * @var string
     */
    private static string $logDir = __DIR__ . '/../../logs';

    /**
     * Error constant
     *
     * @const
     */
    public const ERROR = 'ERROR';

    /**
     * Warning constant
     *
     * @const
     */
    public const WARNING = 'WARNING';

    /**
     * Info constant
     *
     * @const
     */
    public const INFO = 'INFO';

    /**
     * Debug constant
     *
     * @const
     */
    public const DEBUG = 'DEBUG';

    /**
     * Auth constant
     *
     * @const
     */
    public const AUTH = 'AUTH';

    /**
     * Admin constant
     *
     * @const
     */
    public const ADMIN = 'ADMIN';

    /**
     * Email constant
     *
     * @const
     */
    public const EMAIL = 'EMAIL';

    /**
     * Log
     *
     * @param  $level Parameter
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function log($level, $message, $context = []): void
    {
        self::ensureLogDir();

        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userId = $_SESSION['user_id'] ?? 'guest';

        // Форматируем контекст
        $contextStr = empty($context) ? '' : ' | ' . json_encode($context, JSON_UNESCAPED_UNICODE);

        // Формируем строку лога
        $logLine = sprintf(
            "[%s] [%s] [IP: %s] [User: %s] %s%s\n",
            $timestamp,
            $level,
            $ip,
            $userId,
            $message,
            $contextStr
        );

        // Определяем файл лога
        $filename = self::getLogFilename($level);

        // Записываем в файл
        file_put_contents($filename, $logLine, FILE_APPEND | LOCK_EX);

        // Также записываем критичные ошибки в общий лог
        if ($level === self::ERROR) {
            error_log($message);
        }
    }

    /**
     * Error
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function error($message, $context = []): void
    {
        self::log(self::ERROR, $message, $context);
    }

    /**
     * Warning
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function warning($message, $context = []): void
    {
        self::log(self::WARNING, $message, $context);
    }

    /**
     * Info
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function info($message, $context = []): void
    {
        self::log(self::INFO, $message, $context);
    }

    /**
     * Debug
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function debug($message, $context = []): void
    {
        self::log(self::DEBUG, $message, $context);
    }

    /**
     * Auth
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function auth($message, $context = []): void
    {
        self::log(self::AUTH, $message, $context);
    }

    /**
     * Admin
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function admin($message, $context = []): void
    {
        self::log(self::ADMIN, $message, $context);
    }

    /**
     * Email
     *
     * @param  $message Message content
     * @param  $context Parameter
     *
     * @return void
     */
    public static function email($message, $context = []): void
    {
        self::log(self::EMAIL, $message, $context);
    }

    /**
     * Get Log Filename
     *
     * @param  $level Parameter
     *
     * @return string String value
     */
    private static function getLogFilename($level): string
    {
        $date = date('Y-m-d');

        return match ($level) {
            self::AUTH => self::$logDir . sprintf('/auth-%s.log', $date),
            self::ADMIN => self::$logDir . sprintf('/admin-%s.log', $date),
            self::EMAIL => self::$logDir . sprintf('/email-%s.log', $date),
            self::ERROR => self::$logDir . sprintf('/error-%s.log', $date),
            default => self::$logDir . sprintf('/app-%s.log', $date),
        };
    }

    /**
     * Ensure Log Dir
     *
     * @return void
     */
    private static function ensureLogDir(): void
    {
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true);
        }

        // Создаем .gitignore чтобы не коммитить логи
        $gitignore = self::$logDir . '/.gitignore';
        if (!file_exists($gitignore)) {
            file_put_contents($gitignore, "*\n!.gitignore\n");
        }
    }

    /**
     * Cleanup
     *
     * @param  $days Parameter
     *
     * @return void
     */
    public static function cleanup($days = 30): void
    {
        self::ensureLogDir();

        $files = glob(self::$logDir . '/*.log');
        $cutoff = time() - ($days * 24 * 60 * 60);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
            }
        }
    }

    /**
     * Tail
     *
     * @param  $type Parameter
     * @param  $lines Parameter
     *
     * @return array Data array
     */
    public static function tail($type = 'app', $lines = 100): array
    {
        $date = date('Y-m-d');
        $filename = self::$logDir . sprintf('/%s-%s.log', $type, $date);

        if (!file_exists($filename)) {
            return [];
        }

        $file = new SplFileObject($filename);
        $file->seek(PHP_INT_MAX);

        $totalLines = $file->key();

        $startLine = max(0, $totalLines - $lines);
        $result = [];

        $file->seek($startLine);
        while (!$file->eof()) {
            $line = $file->current();
            if (!in_array(trim($line), ['', '0'], true)) {
                $result[] = $line;
            }

            $file->next();
        }

        return $result;
    }
}
