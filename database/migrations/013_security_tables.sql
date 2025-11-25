-- Миграция: Таблицы безопасности
-- Создание: 2025-11-25

-- Таблица заблокированных IP
CREATE TABLE IF NOT EXISTS blocked_ips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    reason VARCHAR(255) NULL,
    blocked_by INT NULL,
    blocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at DATETIME NULL,
    is_permanent TINYINT(1) DEFAULT 0,
    UNIQUE KEY idx_ip (ip_address),
    INDEX idx_expires (expires_at),
    FOREIGN KEY (blocked_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица неудачных попыток входа
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    username VARCHAR(100) NULL,
    user_agent TEXT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success TINYINT(1) DEFAULT 0,
    failure_reason VARCHAR(100) NULL,
    INDEX idx_ip (ip_address),
    INDEX idx_username (username),
    INDEX idx_attempted_at (attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица активных сессий
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT NULL,
    device_info VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active TINYINT(1) DEFAULT 1,
    UNIQUE KEY idx_session (session_id),
    INDEX idx_user (user_id),
    INDEX idx_active (is_active),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Очистка старых записей (автоматически через события MySQL или cron)
-- DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
-- DELETE FROM user_sessions WHERE last_activity < DATE_SUB(NOW(), INTERVAL 7 DAY) AND is_active = 0;
