-- Mock sessions data
-- Run after users are created

SET NAMES utf8mb4;

-- Очищаем старые данные
DELETE FROM user_sessions WHERE 1=1;

-- Добавляем моковые сессии для существующих пользователей
INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, device_info, is_active, last_activity, created_at)
SELECT 
    u.id,
    CONCAT('sess_', MD5(CONCAT(u.id, NOW(), RAND()))),
    CONCAT('192.168.1.', FLOOR(RAND() * 254) + 1),
    CASE FLOOR(RAND() * 4)
        WHEN 0 THEN 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36'
        WHEN 1 THEN 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36'
        WHEN 2 THEN 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1'
        ELSE 'Mozilla/5.0 (Linux; Android 14) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36'
    END,
    CASE FLOOR(RAND() * 3)
        WHEN 0 THEN 'desktop - Chrome'
        WHEN 1 THEN 'mobile - Safari'
        ELSE 'tablet - Firefox'
    END,
    1,
    NOW() - INTERVAL FLOOR(RAND() * 60) MINUTE,
    NOW() - INTERVAL FLOOR(RAND() * 24) HOUR
FROM users u
WHERE u.id <= 10
LIMIT 10;

-- Добавляем неактивные сессии
INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, device_info, is_active, last_activity, created_at)
SELECT 
    u.id,
    CONCAT('sess_old_', MD5(CONCAT(u.id, NOW(), RAND()))),
    CONCAT('10.0.0.', FLOOR(RAND() * 254) + 1),
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/119.0.0.0 Safari/537.36',
    'desktop - Chrome',
    0,
    NOW() - INTERVAL FLOOR(RAND() * 7) DAY,
    NOW() - INTERVAL FLOOR(RAND() * 14) DAY
FROM users u
WHERE u.id > 10 AND u.id <= 20
LIMIT 5;

-- Добавляем записи в login_attempts для статистики
INSERT IGNORE INTO login_attempts (username, ip_address, user_agent, success, attempted_at)
SELECT 
    u.username,
    CONCAT('192.168.1.', FLOOR(RAND() * 254) + 1),
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
    1,
    NOW() - INTERVAL FLOOR(RAND() * 168) HOUR
FROM users u
WHERE u.id <= 30
LIMIT 30;

-- Добавляем activity_logs для топа активных
INSERT IGNORE INTO activity_logs (user_id, action, description, ip_address, created_at)
SELECT 
    u.id,
    CASE FLOOR(RAND() * 5)
        WHEN 0 THEN 'login'
        WHEN 1 THEN 'profile_update'
        WHEN 2 THEN 'password_change'
        WHEN 3 THEN 'settings_update'
        ELSE 'page_view'
    END,
    'User activity',
    CONCAT('192.168.1.', FLOOR(RAND() * 254) + 1),
    NOW() - INTERVAL FLOOR(RAND() * 168) HOUR
FROM users u
CROSS JOIN (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) AS multiplier
WHERE u.id <= 20
LIMIT 100;
