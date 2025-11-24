<?php

namespace AuraUI\Core;

/**
 *  Rate Limiter
 *
 * @package AuraUI\Core
 */
class RateLimiter
{
    /**
     * Db
     *
     * @var mixed
     */
    private $db;

    /**
     *   construct
     */
    public function __construct()
    {
        $this->db = getDB();
        $this->createTableIfNotExists();
    }

    /**
     * Create Table If Not Exists
     *
     * @return void
     */
    private function createTableIfNotExists(): void
    {
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
     * Check
     *
     * @param  $action Parameter
     * @param  $maxAttempts Parameter
     * @param  $windowSeconds Parameter
     *
     * @return bool True on success, false on failure
     */
    public function check($action, $maxAttempts = 5, $windowSeconds = 60): bool
    {
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
            $this->record();
            return true;
        }

        // Проверяем не истекло ли время
        if (strtotime($limit['reset_at']) < time()) {
            // Время истекло, сбрасываем
            $this->reset();
            return true;
        }

        // Проверяем количество попыток
        if ($limit['attempts'] >= $maxAttempts) {
            return false; // Лимит превышен
        }

        // Увеличиваем счетчик
        $this->increment();
        return true;
    }

    /**
     * Get Remaining Time
     *
     * @param  $action Parameter
     */
    public function getRemainingTime($action)
    {
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
     * Clear
     *
     * @return void
     */
    public function clear(): void
    {
        $identifier = $this->getIdentifier();

        $stmt = $this->db->prepare("
            DELETE FROM rate_limits 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
    }

    /**
     * Get Identifier
     *
     * @return string String value
     */
    private function getIdentifier(): string
    {
        // Используем IP + User Agent для идентификации
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        return hash('sha256', $ip . $userAgent);
    }

    /**
     * Record
     *
     * @return void
     */
    private function record(): void
    {
        $resetAt = date('Y-m-d H:i:s', time() + $windowSeconds);

        $stmt = $this->db->prepare("
            INSERT INTO rate_limits (identifier, action, attempts, reset_at)
            VALUES (?, ?, 1, ?)
            ON DUPLICATE KEY UPDATE 
                attempts = 1,
                reset_at = ?
        ");
        $stmt->execute([$identifier, $action, $resetAt, $resetAt]);
    }

    /**
     * Increment
     *
     * @return void
     */
    private function increment(): void
    {
        $stmt = $this->db->prepare("
            UPDATE rate_limits 
            SET attempts = attempts + 1 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$identifier, $action]);
    }

    /**
     * Reset
     *
     * @return void
     */
    private function reset(): void
    {
        $resetAt = date('Y-m-d H:i:s', time() + $windowSeconds);

        $stmt = $this->db->prepare("
            UPDATE rate_limits 
            SET attempts = 1, reset_at = ? 
            WHERE identifier = ? AND action = ?
        ");
        $stmt->execute([$resetAt, $identifier, $action]);
    }

    /**
     * Cleanup
     *
     * @return void
     */
    private function cleanup(): void
    {
        // Удаляем записи старше 24 часов
        $this->db->exec("
            DELETE FROM rate_limits 
            WHERE reset_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ");
    }
}
