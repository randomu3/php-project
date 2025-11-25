-- RBAC, Sessions, API Keys, CMS tables
-- Migration 015

-- Таблица сессий пользователей (расширенная)
CREATE TABLE IF NOT EXISTS user_sessions_extended (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(128) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    user_agent TEXT,
    country VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    device_type VARCHAR(50) DEFAULT 'desktop',
    browser VARCHAR(100) DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_sessions_user (user_id),
    INDEX idx_user_sessions_active (is_active),
    INDEX idx_user_sessions_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- API ключи
CREATE TABLE IF NOT EXISTS api_keys (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    api_key VARCHAR(64) NOT NULL UNIQUE,
    secret_hash VARCHAR(255) NOT NULL,
    permissions JSON DEFAULT NULL,
    rate_limit INT DEFAULT 1000,
    rate_period VARCHAR(20) DEFAULT 'hour',
    requests_count INT DEFAULT 0,
    last_used_at TIMESTAMP NULL,
    expires_at TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_api_keys_key (api_key),
    INDEX idx_api_keys_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Статистика использования API
CREATE TABLE IF NOT EXISTS api_usage_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    api_key_id INT NOT NULL,
    endpoint VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    status_code INT NOT NULL,
    response_time_ms INT DEFAULT 0,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (api_key_id) REFERENCES api_keys(id) ON DELETE CASCADE,
    INDEX idx_api_usage_key (api_key_id),
    INDEX idx_api_usage_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CMS Страницы
CREATE TABLE IF NOT EXISTS cms_pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    template VARCHAR(100) DEFAULT 'default',
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    author_id INT,
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES cms_pages(id) ON DELETE SET NULL,
    INDEX idx_pages_slug (slug),
    INDEX idx_pages_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Меню навигации
CREATE TABLE IF NOT EXISTS cms_menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Пункты меню
CREATE TABLE IF NOT EXISTS cms_menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(500),
    page_id INT DEFAULT NULL,
    target VARCHAR(20) DEFAULT '_self',
    icon VARCHAR(50) DEFAULT NULL,
    css_class VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (menu_id) REFERENCES cms_menus(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES cms_menu_items(id) ON DELETE CASCADE,
    FOREIGN KEY (page_id) REFERENCES cms_pages(id) ON DELETE SET NULL,
    INDEX idx_menu_items_menu (menu_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Медиа библиотека
CREATE TABLE IF NOT EXISTS media_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_size INT NOT NULL,
    path VARCHAR(500) NOT NULL,
    thumbnail_path VARCHAR(500) DEFAULT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    title VARCHAR(255) DEFAULT NULL,
    description TEXT,
    folder VARCHAR(100) DEFAULT 'uploads',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_media_folder (folder),
    INDEX idx_media_mime (mime_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cron задачи
CREATE TABLE IF NOT EXISTS cron_jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    command VARCHAR(500) NOT NULL,
    schedule VARCHAR(100) NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    last_run_at TIMESTAMP NULL,
    next_run_at TIMESTAMP NULL,
    last_status ENUM('success', 'failed', 'running') DEFAULT NULL,
    last_output TEXT,
    last_duration_ms INT DEFAULT 0,
    run_count INT DEFAULT 0,
    fail_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cron_active (is_active),
    INDEX idx_cron_next (next_run_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- История выполнения cron
CREATE TABLE IF NOT EXISTS cron_job_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    status ENUM('success', 'failed') NOT NULL,
    output TEXT,
    duration_ms INT DEFAULT 0,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    finished_at TIMESTAMP NULL,
    FOREIGN KEY (job_id) REFERENCES cron_jobs(id) ON DELETE CASCADE,
    INDEX idx_cron_logs_job (job_id),
    INDEX idx_cron_logs_date (started_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Очередь email
CREATE TABLE IF NOT EXISTS email_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    to_email VARCHAR(255) NOT NULL,
    to_name VARCHAR(255) DEFAULT NULL,
    subject VARCHAR(255) NOT NULL,
    body LONGTEXT NOT NULL,
    template_id INT DEFAULT NULL,
    priority INT DEFAULT 0,
    status ENUM('pending', 'processing', 'sent', 'failed') DEFAULT 'pending',
    attempts INT DEFAULT 0,
    max_attempts INT DEFAULT 3,
    error_message TEXT,
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES email_templates(id) ON DELETE SET NULL,
    INDEX idx_email_queue_status (status),
    INDEX idx_email_queue_scheduled (scheduled_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Системные логи
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level ENUM('debug', 'info', 'warning', 'error', 'critical') NOT NULL,
    channel VARCHAR(50) DEFAULT 'app',
    message TEXT NOT NULL,
    context JSON DEFAULT NULL,
    file VARCHAR(255) DEFAULT NULL,
    line INT DEFAULT NULL,
    user_id INT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_logs_level (level),
    INDEX idx_logs_channel (channel),
    INDEX idx_logs_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
