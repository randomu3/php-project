-- Тестовые данные для раздела безопасности
-- ТОЛЬКО ДЛЯ DEV ОКРУЖЕНИЯ!

-- Тестовые попытки входа (успешные и неудачные)
INSERT INTO login_attempts (ip_address, username, user_agent, success, failure_reason, attempted_at) VALUES
-- Успешные входы
('192.168.1.100', 'demiz99', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0', 1, NULL, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
('192.168.1.100', 'demiz99', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0', 1, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('10.0.0.50', 'testuser1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1.15', 1, NULL, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('172.16.0.25', 'testadmin', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/121.0', 1, NULL, DATE_SUB(NOW(), INTERVAL 3 HOUR)),

-- Неудачные попытки - подозрительный IP (много попыток)
('45.33.32.156', 'admin', 'Mozilla/5.0 (compatible; Googlebot/2.1)', 0, 'User not found', DATE_SUB(NOW(), INTERVAL 30 MINUTE)),
('45.33.32.156', 'administrator', 'Mozilla/5.0 (compatible; Googlebot/2.1)', 0, 'User not found', DATE_SUB(NOW(), INTERVAL 29 MINUTE)),
('45.33.32.156', 'root', 'Mozilla/5.0 (compatible; Googlebot/2.1)', 0, 'User not found', DATE_SUB(NOW(), INTERVAL 28 MINUTE)),
('45.33.32.156', 'test', 'Mozilla/5.0 (compatible; Googlebot/2.1)', 0, 'User not found', DATE_SUB(NOW(), INTERVAL 27 MINUTE)),
('45.33.32.156', 'user', 'Mozilla/5.0 (compatible; Googlebot/2.1)', 0, 'User not found', DATE_SUB(NOW(), INTERVAL 26 MINUTE)),

-- Ещё один подозрительный IP
('185.220.101.42', 'demiz99', 'python-requests/2.28.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 2 HOUR)),
('185.220.101.42', 'demiz99', 'python-requests/2.28.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 2 HOUR) + INTERVAL 1 MINUTE),
('185.220.101.42', 'demiz99', 'python-requests/2.28.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 2 HOUR) + INTERVAL 2 MINUTE),
('185.220.101.42', 'demiz99', 'python-requests/2.28.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 2 HOUR) + INTERVAL 3 MINUTE),

-- Третий подозрительный IP
('91.240.118.172', 'testuser1', 'curl/7.68.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 5 HOUR)),
('91.240.118.172', 'testuser2', 'curl/7.68.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 5 HOUR) + INTERVAL 5 MINUTE),
('91.240.118.172', 'testuser3', 'curl/7.68.0', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 5 HOUR) + INTERVAL 10 MINUTE),

-- Обычные неудачные попытки (забыл пароль)
('192.168.1.100', 'testuser1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) Safari/604.1', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 6 HOUR)),
('10.0.0.75', 'inactive', 'Mozilla/5.0 (Android 14) Chrome/120.0.0.0 Mobile', 0, 'Wrong password', DATE_SUB(NOW(), INTERVAL 12 HOUR)),

-- Попытка входа с неподтверждённым email
('192.168.1.200', 'unverified', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Edge/120.0.0.0', 0, 'Email not verified', DATE_SUB(NOW(), INTERVAL 4 HOUR)),

-- Попытка входа заблокированного пользователя
('192.168.1.150', 'blockeduser', 'Mozilla/5.0 (Linux; Android 13) Chrome/120.0.0.0', 0, 'Account locked', DATE_SUB(NOW(), INTERVAL 8 HOUR));

-- Заблокированные IP
INSERT INTO blocked_ips (ip_address, reason, blocked_by, expires_at, is_permanent, blocked_at) VALUES
('45.33.32.156', 'Брутфорс атака - множественные попытки подбора логина', 1, NULL, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('185.220.101.42', 'Подозрительная активность - автоматизированные запросы', 1, DATE_ADD(NOW(), INTERVAL 7 DAY), 0, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('103.21.244.0', 'Спам регистрации', 1, DATE_ADD(NOW(), INTERVAL 30 DAY), 0, DATE_SUB(NOW(), INTERVAL 5 DAY));

-- Активные сессии
INSERT INTO user_sessions (user_id, session_id, ip_address, user_agent, device_info, created_at, last_activity, is_active) VALUES
(1, 'sess_abc123def456', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0', 'Windows / Chrome', DATE_SUB(NOW(), INTERVAL 2 HOUR), NOW(), 1),
(1, 'sess_mobile789xyz', '192.168.1.105', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) Safari/604.1', 'iPhone / Safari', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_SUB(NOW(), INTERVAL 3 HOUR), 1),
(2, 'sess_test1user111', '10.0.0.50', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) Safari/605.1.15', 'Mac / Safari', DATE_SUB(NOW(), INTERVAL 5 HOUR), DATE_SUB(NOW(), INTERVAL 1 HOUR), 1),
(9, 'sess_admin999aaa', '172.16.0.25', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Firefox/121.0', 'Windows / Firefox', DATE_SUB(NOW(), INTERVAL 3 HOUR), DATE_SUB(NOW(), INTERVAL 30 MINUTE), 1),
(3, 'sess_user2bbb222', '10.0.0.75', 'Mozilla/5.0 (Android 14) Chrome/120.0.0.0 Mobile', 'Android / Chrome', DATE_SUB(NOW(), INTERVAL 12 HOUR), DATE_SUB(NOW(), INTERVAL 6 HOUR), 1);

-- Дополнительные записи в журнал активности
INSERT INTO activity_logs (user_id, action, description, entity_type, entity_id, created_at) VALUES
(1, 'user.login', 'Пользователь demiz99 вошел в систему', 'user', 1, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(1, 'user.update_profile', 'Обновлен username: demiz99', 'user', 1, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(1, 'user.change_password', 'Пользователь изменил пароль', 'user', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 'user.login', 'Пользователь testuser1 вошел в систему', 'user', 2, DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(9, 'user.login', 'Пользователь testadmin вошел в систему', 'user', 9, DATE_SUB(NOW(), INTERVAL 4 HOUR)),
(1, 'admin.block_user', 'Заблокирован пользователь blockeduser', 'user', 7, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(1, 'admin.toggle_admin', 'Назначен администратором: testadmin', 'user', 9, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 'user.login', 'Пользователь testuser2 вошел в систему', 'user', 3, DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(1, 'user.update_profile', 'Загружен новый аватар', 'user', 1, DATE_SUB(NOW(), INTERVAL 12 HOUR)),
(4, 'user.login', 'Пользователь testuser3 вошел в систему', 'user', 4, DATE_SUB(NOW(), INTERVAL 8 HOUR));
