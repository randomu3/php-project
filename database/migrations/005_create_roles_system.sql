-- Миграция: Система ролей и прав доступа
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Таблица ролей
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица прав доступа
CREATE TABLE IF NOT EXISTS permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    display_name VARCHAR(150) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_name (name),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Связь ролей и прав (многие ко многим)
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Связь пользователей и ролей (многие ко многим)
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Вставка базовых ролей
INSERT INTO roles (name, display_name, description) VALUES
('admin', 'Администратор', 'Полный доступ ко всем функциям системы'),
('moderator', 'Модератор', 'Управление контентом и пользователями'),
('user', 'Пользователь', 'Базовый доступ к функциям сайта');

-- Вставка базовых прав доступа
INSERT INTO permissions (name, display_name, description, category) VALUES
-- Управление пользователями
('users.view', 'Просмотр пользователей', 'Просмотр списка пользователей', 'users'),
('users.create', 'Создание пользователей', 'Создание новых пользователей', 'users'),
('users.edit', 'Редактирование пользователей', 'Редактирование данных пользователей', 'users'),
('users.delete', 'Удаление пользователей', 'Удаление пользователей из системы', 'users'),
('users.ban', 'Блокировка пользователей', 'Блокировка и разблокировка пользователей', 'users'),

-- Управление ролями
('roles.view', 'Просмотр ролей', 'Просмотр списка ролей', 'roles'),
('roles.create', 'Создание ролей', 'Создание новых ролей', 'roles'),
('roles.edit', 'Редактирование ролей', 'Редактирование ролей и их прав', 'roles'),
('roles.delete', 'Удаление ролей', 'Удаление ролей из системы', 'roles'),

-- Управление настройками
('settings.view', 'Просмотр настроек', 'Просмотр настроек системы', 'settings'),
('settings.edit', 'Редактирование настроек', 'Изменение настроек системы', 'settings'),

-- Управление email
('emails.send', 'Отправка email', 'Отправка email пользователям', 'emails'),
('emails.templates', 'Управление шаблонами', 'Создание и редактирование шаблонов email', 'emails'),

-- Просмотр логов
('logs.view', 'Просмотр логов', 'Просмотр логов действий пользователей', 'logs'),
('logs.delete', 'Удаление логов', 'Удаление старых логов', 'logs'),

-- Управление уведомлениями
('notifications.send', 'Отправка уведомлений', 'Отправка уведомлений пользователям', 'notifications'),
('notifications.manage', 'Управление уведомлениями', 'Управление системными уведомлениями', 'notifications');

-- Назначение прав администратору (все права)
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
CROSS JOIN permissions p
WHERE r.name = 'admin';

-- Назначение прав модератору
INSERT INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
CROSS JOIN permissions p
WHERE r.name = 'moderator'
AND p.name IN (
    'users.view', 'users.edit', 'users.ban',
    'emails.send', 'emails.templates',
    'logs.view',
    'notifications.send'
);

-- Назначение прав обычному пользователю (минимальные)
-- У обычных пользователей нет специальных прав, только базовый доступ

-- Миграция существующих админов
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
CROSS JOIN roles r
WHERE u.is_admin = 1 AND r.name = 'admin';

-- Миграция обычных пользователей
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u
CROSS JOIN roles r
WHERE u.is_admin = 0 AND r.name = 'user';
