-- Mock sessions data
-- Run after users are created

SET NAMES utf8mb4;

-- Очищаем старые данные
DELETE FROM user_sessions WHERE 1=1;

-- Добавляем активные сессии с разными устройствами и браузерами
INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, device_info, country, city, is_active, last_activity, created_at)
VALUES
-- Desktop сессии
(1, 'sess_admin_current_001', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36', 'desktop - Chrome', 'Россия', 'Москва', 1, NOW(), NOW() - INTERVAL 1 HOUR),
(2, 'sess_user_002', '192.168.1.45', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36', 'desktop - Chrome', 'Россия', 'Санкт-Петербург', 1, NOW() - INTERVAL 5 MINUTE, NOW() - INTERVAL 2 HOUR),
(3, 'sess_user_003', '192.168.1.78', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36', 'desktop - Chrome', 'Россия', 'Казань', 1, NOW() - INTERVAL 15 MINUTE, NOW() - INTERVAL 3 HOUR),
(4, 'sess_user_004', '192.168.1.156', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:121.0) Gecko/20100101 Firefox/121.0', 'desktop - Firefox', 'Россия', 'Новосибирск', 1, NOW() - INTERVAL 30 MINUTE, NOW() - INTERVAL 4 HOUR),
(5, 'sess_user_005', '192.168.1.201', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 Safari/605.1.15', 'desktop - Safari', 'Россия', 'Екатеринбург', 1, NOW() - INTERVAL 45 MINUTE, NOW() - INTERVAL 5 HOUR),

-- Mobile сессии
(6, 'sess_mobile_006', '192.168.1.50', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1', 'mobile - Safari', 'Россия', 'Краснодар', 1, NOW() - INTERVAL 10 MINUTE, NOW() - INTERVAL 1 HOUR),
(7, 'sess_mobile_007', '192.168.1.88', 'Mozilla/5.0 (Linux; Android 14; SM-S918B) AppleWebKit/537.36 Chrome/120.0.0.0 Mobile Safari/537.36', 'mobile - Chrome', 'Россия', 'Нижний Новгород', 1, NOW() - INTERVAL 20 MINUTE, NOW() - INTERVAL 2 HOUR),

-- Tablet сессии
(8, 'sess_tablet_008', '192.168.1.120', 'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Safari/605.1.15', 'tablet - Safari', 'Россия', 'Самара', 1, NOW() - INTERVAL 25 MINUTE, NOW() - INTERVAL 3 HOUR),
(9, 'sess_tablet_009', '192.168.1.135', 'Mozilla/5.0 (Linux; Android 14; Tab S9) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36', 'tablet - Chrome', 'Россия', 'Ростов-на-Дону', 1, NOW() - INTERVAL 35 MINUTE, NOW() - INTERVAL 4 HOUR),

-- Дополнительные активные сессии
(10, 'sess_user_010', '10.0.0.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Edge/120.0.0.0', 'desktop - Edge', 'Россия', 'Воронеж', 1, NOW() - INTERVAL 40 MINUTE, NOW() - INTERVAL 5 HOUR),
(1, 'sess_admin_mobile_011', '192.168.1.200', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile Safari', 'mobile - Safari', 'Россия', 'Москва', 1, NOW() - INTERVAL 50 MINUTE, NOW() - INTERVAL 6 HOUR),
(2, 'sess_user_tablet_012', '192.168.1.210', 'Mozilla/5.0 (iPad; CPU OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Safari', 'tablet - Safari', 'Россия', 'Санкт-Петербург', 1, NOW() - INTERVAL 55 MINUTE, NOW() - INTERVAL 7 HOUR);

-- Неактивные (завершенные) сессии для истории
INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, device_info, country, city, is_active, last_activity, created_at)
VALUES
(1, 'sess_old_001', '192.168.1.10', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/119.0.0.0', 'desktop - Chrome', 'Россия', 'Москва', 0, NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 2 DAY),
(2, 'sess_old_002', '192.168.1.20', 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0) Safari', 'mobile - Safari', 'Россия', 'Санкт-Петербург', 0, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 3 DAY),
(3, 'sess_old_003', '192.168.1.30', 'Mozilla/5.0 (Windows NT 10.0) Firefox/120.0', 'desktop - Firefox', 'Россия', 'Казань', 0, NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 4 DAY),
(4, 'sess_old_004', '192.168.1.40', 'Mozilla/5.0 (Macintosh) Safari/605.1.15', 'desktop - Safari', 'Россия', 'Новосибирск', 0, NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 5 DAY),
(5, 'sess_old_005', '192.168.1.50', 'Mozilla/5.0 (Linux; Android 13) Chrome/119.0.0.0 Mobile', 'mobile - Chrome', 'Россия', 'Екатеринбург', 0, NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 6 DAY),
(6, 'sess_old_006', '192.168.1.60', 'Mozilla/5.0 (iPad) Safari/605.1.15', 'tablet - Safari', 'Россия', 'Краснодар', 0, NOW() - INTERVAL 6 DAY, NOW() - INTERVAL 7 DAY),
(7, 'sess_old_007', '10.0.0.70', 'Mozilla/5.0 (Windows NT 10.0) Edge/119.0.0.0', 'desktop - Edge', 'Россия', 'Нижний Новгород', 0, NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 8 DAY),
(8, 'sess_old_008', '10.0.0.80', 'Mozilla/5.0 (Linux; Android 14) Chrome/120.0.0.0', 'mobile - Chrome', 'Россия', 'Самара', 0, NOW() - INTERVAL 8 DAY, NOW() - INTERVAL 9 DAY);

-- Добавляем записи в login_attempts для статистики
INSERT IGNORE INTO login_attempts (username, ip_address, user_agent, success, attempted_at)
VALUES
('admin', '192.168.1.100', 'Mozilla/5.0 Chrome/120.0.0.0', 1, NOW() - INTERVAL 1 HOUR),
('user1', '192.168.1.45', 'Mozilla/5.0 Chrome/120.0.0.0', 1, NOW() - INTERVAL 2 HOUR),
('user2', '192.168.1.78', 'Mozilla/5.0 Chrome/120.0.0.0', 1, NOW() - INTERVAL 3 HOUR),
('user3', '192.168.1.156', 'Mozilla/5.0 Firefox/121.0', 1, NOW() - INTERVAL 4 HOUR),
('user4', '192.168.1.201', 'Mozilla/5.0 Safari/605.1.15', 1, NOW() - INTERVAL 5 HOUR),
('user5', '192.168.1.50', 'Mozilla/5.0 Mobile Safari', 1, NOW() - INTERVAL 6 HOUR),
('hacker', '45.33.32.156', 'Mozilla/5.0 Chrome/120.0.0.0', 0, NOW() - INTERVAL 30 MINUTE),
('admin', '185.220.101.1', 'Mozilla/5.0 Chrome/120.0.0.0', 0, NOW() - INTERVAL 45 MINUTE),
('test', '192.168.1.99', 'Mozilla/5.0 Chrome/120.0.0.0', 0, NOW() - INTERVAL 1 HOUR);

-- Добавляем activity_logs для топа активных
INSERT IGNORE INTO activity_logs (user_id, action, description, ip_address, created_at)
VALUES
(1, 'login', 'Успешный вход в систему', '192.168.1.100', NOW() - INTERVAL 1 HOUR),
(1, 'profile_update', 'Обновление профиля', '192.168.1.100', NOW() - INTERVAL 2 HOUR),
(2, 'login', 'Успешный вход в систему', '192.168.1.45', NOW() - INTERVAL 2 HOUR),
(2, 'password_change', 'Смена пароля', '192.168.1.45', NOW() - INTERVAL 3 HOUR),
(3, 'login', 'Успешный вход в систему', '192.168.1.78', NOW() - INTERVAL 3 HOUR),
(3, 'settings_update', 'Изменение настроек', '192.168.1.78', NOW() - INTERVAL 4 HOUR),
(4, 'login', 'Успешный вход в систему', '192.168.1.156', NOW() - INTERVAL 4 HOUR),
(5, 'login', 'Успешный вход в систему', '192.168.1.201', NOW() - INTERVAL 5 HOUR),
(6, 'login', 'Успешный вход в систему', '192.168.1.50', NOW() - INTERVAL 6 HOUR),
(7, 'login', 'Успешный вход в систему', '192.168.1.88', NOW() - INTERVAL 7 HOUR),
(8, 'login', 'Успешный вход в систему', '192.168.1.120', NOW() - INTERVAL 8 HOUR),
(9, 'login', 'Успешный вход в систему', '192.168.1.135', NOW() - INTERVAL 9 HOUR),
(10, 'login', 'Успешный вход в систему', '10.0.0.15', NOW() - INTERVAL 10 HOUR);
