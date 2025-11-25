-- Migration: Admin settings and notifications system
-- Created: 2024

-- Системные настройки
CREATE TABLE IF NOT EXISTS system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('string', 'int', 'bool', 'json') DEFAULT 'string',
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Уведомления для админов
CREATE TABLE IF NOT EXISTS admin_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('registration', 'security', 'system', 'report') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    data JSON,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Настройки уведомлений админа
CREATE TABLE IF NOT EXISTS admin_notification_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    notify_new_registration BOOLEAN DEFAULT TRUE,
    notify_suspicious_activity BOOLEAN DEFAULT TRUE,
    notify_failed_logins BOOLEAN DEFAULT TRUE,
    email_reports BOOLEAN DEFAULT FALSE,
    email_report_frequency ENUM('daily', 'weekly', 'monthly') DEFAULT 'daily',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Начальные системные настройки
INSERT INTO system_settings (setting_key, setting_value, setting_type, description) VALUES
('max_login_attempts', '5', 'int', 'Максимум попыток входа до блокировки'),
('lockout_duration', '15', 'int', 'Время блокировки в минутах'),
('session_timeout', '3600', 'int', 'Таймаут сессии в секундах'),
('password_min_length', '8', 'int', 'Минимальная длина пароля'),
('require_email_verification', '1', 'bool', 'Требовать подтверждение email'),
('allow_registration', '1', 'bool', 'Разрешить регистрацию'),
('maintenance_mode', '0', 'bool', 'Режим обслуживания'),
('site_name', 'AuraUI', 'string', 'Название сайта'),
('admin_email', '', 'string', 'Email администратора для уведомлений'),
('max_upload_size', '5', 'int', 'Максимальный размер загрузки в MB'),
('token_expiry_hours', '24', 'int', 'Срок действия токена сброса пароля в часах')
ON DUPLICATE KEY UPDATE setting_key = setting_key;
