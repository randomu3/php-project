-- Fix permissions encoding
-- Migration 016

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Удаляем старые права и создаём заново с правильной кодировкой
DELETE FROM role_permissions;
DELETE FROM permissions;

-- Права для пользователей
INSERT INTO permissions (name, display_name, description, category) VALUES
-- Users
('users.view', 'Просмотр пользователей', 'Просмотр списка пользователей', 'users'),
('users.create', 'Создание пользователей', 'Создание новых пользователей', 'users'),
('users.edit', 'Редактирование пользователей', 'Редактирование данных пользователей', 'users'),
('users.delete', 'Удаление пользователей', 'Удаление пользователей из системы', 'users'),
('users.block', 'Блокировка пользователей', 'Блокировка и разблокировка пользователей', 'users'),

-- Roles
('roles.view', 'Просмотр ролей', 'Просмотр списка ролей', 'roles'),
('roles.create', 'Создание ролей', 'Создание новых ролей', 'roles'),
('roles.edit', 'Редактирование ролей', 'Редактирование ролей и их прав', 'roles'),
('roles.delete', 'Удаление ролей', 'Удаление ролей из системы', 'roles'),

-- Files
('files.view', 'Просмотр файлов', 'Просмотр медиа библиотеки', 'files'),
('files.upload', 'Загрузка файлов', 'Загрузка новых файлов', 'files'),
('files.delete', 'Удаление файлов', 'Удаление файлов из библиотеки', 'files'),

-- CMS
('pages.view', 'Просмотр страниц', 'Просмотр CMS страниц', 'cms'),
('pages.create', 'Создание страниц', 'Создание новых страниц', 'cms'),
('pages.edit', 'Редактирование страниц', 'Редактирование страниц', 'cms'),
('pages.delete', 'Удаление страниц', 'Удаление страниц', 'cms'),
('menus.manage', 'Управление меню', 'Управление навигационными меню', 'cms'),

-- API
('api.manage', 'Управление API', 'Управление API ключами', 'api'),

-- Cron
('cron.view', 'Просмотр задач', 'Просмотр cron задач', 'cron'),
('cron.manage', 'Управление задачами', 'Управление cron задачами', 'cron'),

-- Sessions
('sessions.view', 'Просмотр сессий', 'Просмотр активных сессий', 'sessions'),
('sessions.manage', 'Управление сессиями', 'Принудительное завершение сессий', 'sessions'),

-- Logs
('logs.view', 'Просмотр логов', 'Просмотр логов действий пользователей', 'logs'),
('logs.delete', 'Удаление логов', 'Удаление старых логов', 'logs'),

-- Settings
('settings.view', 'Просмотр настроек', 'Просмотр настроек системы', 'settings'),
('settings.edit', 'Редактирование настроек', 'Изменение настроек системы', 'settings'),

-- System
('system.logs', 'Системные логи', 'Просмотр системных логов', 'system'),
('system.monitor', 'Мониторинг системы', 'Просмотр состояния системы', 'system'),

-- Notifications
('notifications.send', 'Отправка уведомлений', 'Отправка уведомлений пользователям', 'notifications'),
('notifications.manage', 'Управление уведомлениями', 'Управление системными уведомлениями', 'notifications'),

-- Emails
('emails.send', 'Отправка email', 'Отправка email пользователям', 'emails'),
('emails.templates', 'Управление шаблонами', 'Создание и редактирование шаблонов email', 'emails');

-- Назначаем все права админу (role_id = 1)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- Назначаем базовые права модератору (role_id = 2)
INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, id FROM permissions WHERE name IN (
    'users.view', 'users.block',
    'files.view', 'files.upload',
    'pages.view',
    'logs.view',
    'sessions.view'
);
