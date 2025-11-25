-- Mock users for testing
-- Creates 50 test users with random registration dates
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Generate mock users with varied registration dates
INSERT IGNORE INTO users (username, email, password_hash, is_admin, email_verified, created_at) VALUES
('user_alex', 'alex@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 45 DAY)),
('user_maria', 'maria@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 44 DAY)),
('user_ivan', 'ivan@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 43 DAY)),
('user_anna', 'anna@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 42 DAY)),
('user_dmitry', 'dmitry@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 40 DAY)),
('user_elena', 'elena@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 38 DAY)),
('user_sergey', 'sergey@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 37 DAY)),
('user_olga', 'olga@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 35 DAY)),
('user_pavel', 'pavel@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 34 DAY)),
('user_natalia', 'natalia@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 32 DAY)),
('user_andrey', 'andrey@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 30 DAY)),
('user_kate', 'kate@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 29 DAY)),
('user_viktor', 'viktor@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 28 DAY)),
('user_julia', 'julia@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 27 DAY)),
('user_maxim', 'maxim@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 25 DAY)),
('user_svetlana', 'svetlana@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 24 DAY)),
('user_nikolay', 'nikolay@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 23 DAY)),
('user_irina', 'irina@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 22 DAY)),
('user_roman', 'roman@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 21 DAY)),
('user_tatiana', 'tatiana@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 20 DAY)),
('user_oleg', 'oleg@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 19 DAY)),
('user_marina', 'marina@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 18 DAY)),
('user_artem', 'artem@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 17 DAY)),
('user_oksana', 'oksana@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 16 DAY)),
('user_denis', 'denis@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 15 DAY)),
('user_vera', 'vera@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 14 DAY)),
('user_kirill', 'kirill@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 13 DAY)),
('user_alina', 'alina@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 12 DAY)),
('user_evgeny', 'evgeny@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 11 DAY)),
('user_daria', 'daria@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 10 DAY)),
('user_vlad', 'vlad@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 9 DAY)),
('user_polina', 'polina@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 8 DAY)),
('user_stanislav', 'stanislav@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 7 DAY)),
('user_yana', 'yana@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
('user_igor', 'igor@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),
('user_kristina', 'kristina@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 5 DAY)),
('user_anton', 'anton@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
('user_lena', 'lena@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
('user_vadim', 'vadim@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('user_nastya', 'nastya@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
('user_boris', 'boris@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('user_galina', 'galina@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('user_timur', 'timur@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
('user_valeria', 'valeria@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('user_georgy', 'georgy@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('user_lyudmila', 'lyudmila@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('user_ruslan', 'ruslan@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, NOW()),
('user_kseniya', 'kseniya@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, NOW()),
('user_yaroslav', 'yaroslav@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 1, NOW()),
('user_diana', 'diana@example.com', '$argon2id$v=19$m=65536,t=4,p=1$test', 0, 0, NOW());

-- Assign user role to all new users
INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
CROSS JOIN roles r
WHERE r.name = 'user'
AND u.username LIKE 'user_%'
AND u.id NOT IN (SELECT user_id FROM user_roles WHERE role_id = r.id);
