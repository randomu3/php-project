-- Тестовые пользователи для разработки
-- ТОЛЬКО ДЛЯ DEV ОКРУЖЕНИЯ!

-- Пароль для всех тестовых пользователей: Test1234
-- Хеш Argon2ID для "Test1234"
SET @test_password = '$argon2id$v=19$m=65536,t=4,p=1$dGVzdHNhbHQxMjM0NTY$K8H+L9Q5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Z5Y';

-- Обычные активные пользователи
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, created_at, last_login) VALUES
('testuser1', 'testuser1@example.com', @test_password, 0, 1, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
('testuser2', 'testuser2@example.com', @test_password, 0, 1, DATE_SUB(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
('testuser3', 'testuser3@example.com', @test_password, 0, 1, DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
('activeuser', 'active@example.com', @test_password, 0, 1, DATE_SUB(NOW(), INTERVAL 15 DAY), NOW());

-- Пользователь с неподтверждённым email
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, email_verification_token, email_verification_expires, created_at) VALUES
('unverified', 'unverified@example.com', @test_password, 0, 0, 'test_verification_token_123', DATE_ADD(NOW(), INTERVAL 24 HOUR), DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Заблокированный пользователь
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, locked_until, failed_attempts, created_at) VALUES
('blockeduser', 'blocked@example.com', @test_password, 0, 1, DATE_ADD(NOW(), INTERVAL 100 YEAR), 999, DATE_SUB(NOW(), INTERVAL 10 DAY));

-- Пользователь с временной блокировкой (15 минут)
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, locked_until, failed_attempts, created_at, last_login) VALUES
('tempblocked', 'tempblocked@example.com', @test_password, 0, 1, DATE_ADD(NOW(), INTERVAL 15 MINUTE), 5, DATE_SUB(NOW(), INTERVAL 7 DAY), DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- Тестовый администратор
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, created_at, last_login) VALUES
('testadmin', 'testadmin@example.com', @test_password, 1, 1, DATE_SUB(NOW(), INTERVAL 60 DAY), DATE_SUB(NOW(), INTERVAL 3 HOUR));

-- Неактивный пользователь (никогда не входил)
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, created_at, last_login) VALUES
('inactive', 'inactive@example.com', @test_password, 0, 1, DATE_SUB(NOW(), INTERVAL 90 DAY), NULL);

-- Добавляем тестовые уведомления для первого тестового пользователя
INSERT IGNORE INTO notifications (user_id, type, title, message, icon, is_read, created_at)
SELECT id, 'info', 'Добро пожаловать!', 'Это тестовое уведомление', 'bell', 0, NOW()
FROM users WHERE username = 'testuser1' LIMIT 1;

INSERT IGNORE INTO notifications (user_id, type, title, message, icon, is_read, created_at)
SELECT id, 'success', 'Профиль обновлён', 'Ваш профиль успешно обновлён', 'check-circle', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)
FROM users WHERE username = 'testuser1' LIMIT 1;
