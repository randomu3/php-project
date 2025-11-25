-- Seed data for admin features
-- Cron jobs, menus, sample pages

-- Примеры cron задач
INSERT IGNORE INTO cron_jobs (name, command, schedule, description, is_active) VALUES
('cleanup_sessions', 'php /var/www/html/cron/cleanup-sessions.php', '0 */6 * * *', 'Очистка устаревших сессий каждые 6 часов', 1),
('process_email_queue', 'php /var/www/html/cron/process-emails.php', '*/5 * * * *', 'Обработка очереди email каждые 5 минут', 1),
('cleanup_logs', 'php /var/www/html/cron/cleanup-logs.php', '0 3 * * *', 'Очистка старых логов в 3:00', 1),
('backup_database', 'php /var/www/html/cron/backup-db.php', '0 2 * * *', 'Резервное копирование БД в 2:00', 0),
('generate_sitemap', 'php /var/www/html/cron/generate-sitemap.php', '0 4 * * 0', 'Генерация sitemap каждое воскресенье', 0);

-- Главное меню
INSERT IGNORE INTO cms_menus (id, name, location) VALUES
(1, 'Главное меню', 'header'),
(2, 'Меню футера', 'footer'),
(3, 'Боковое меню', 'sidebar');

-- Пункты главного меню
INSERT IGNORE INTO cms_menu_items (menu_id, title, url, sort_order, is_active) VALUES
(1, 'Главная', '/', 1, 1),
(1, 'О нас', '/about', 2, 1),
(1, 'Контакты', '/contacts', 3, 1);

-- Пункты меню футера
INSERT IGNORE INTO cms_menu_items (menu_id, title, url, sort_order, is_active) VALUES
(2, 'Политика конфиденциальности', '/privacy', 1, 1),
(2, 'Условия использования', '/terms', 2, 1);

-- Примеры страниц
INSERT IGNORE INTO cms_pages (slug, title, content, meta_title, meta_description, status, sort_order) VALUES
('about', 'О нас', '<h2>О нашей компании</h2><p>Добро пожаловать! Мы рады видеть вас на нашем сайте.</p>', 'О нас | Наш сайт', 'Информация о нашей компании и команде', 'published', 1),
('contacts', 'Контакты', '<h2>Свяжитесь с нами</h2><p>Email: info@example.com</p><p>Телефон: +7 (999) 123-45-67</p>', 'Контакты | Наш сайт', 'Контактная информация для связи', 'published', 2),
('privacy', 'Политика конфиденциальности', '<h2>Политика конфиденциальности</h2><p>Мы заботимся о вашей приватности...</p>', 'Политика конфиденциальности', 'Политика обработки персональных данных', 'published', 3),
('terms', 'Условия использования', '<h2>Условия использования</h2><p>Используя наш сайт, вы соглашаетесь...</p>', 'Условия использования', 'Правила и условия использования сайта', 'published', 4);

-- Дополнительные permissions для новых функций
INSERT IGNORE INTO permissions (name, display_name, description, category) VALUES
('files.view', 'Просмотр файлов', 'Просмотр медиа библиотеки', 'files'),
('files.upload', 'Загрузка файлов', 'Загрузка новых файлов', 'files'),
('files.delete', 'Удаление файлов', 'Удаление файлов из библиотеки', 'files'),
('pages.view', 'Просмотр страниц', 'Просмотр CMS страниц', 'cms'),
('pages.create', 'Создание страниц', 'Создание новых страниц', 'cms'),
('pages.edit', 'Редактирование страниц', 'Редактирование страниц', 'cms'),
('pages.delete', 'Удаление страниц', 'Удаление страниц', 'cms'),
('menus.manage', 'Управление меню', 'Управление навигационными меню', 'cms'),
('api.manage', 'Управление API', 'Управление API ключами', 'api'),
('cron.view', 'Просмотр задач', 'Просмотр cron задач', 'cron'),
('cron.manage', 'Управление задачами', 'Управление cron задачами', 'cron'),
('sessions.view', 'Просмотр сессий', 'Просмотр активных сессий', 'sessions'),
('sessions.manage', 'Управление сессиями', 'Принудительное завершение сессий', 'sessions'),
('system.logs', 'Системные логи', 'Просмотр системных логов', 'system'),
('system.monitor', 'Мониторинг системы', 'Просмотр состояния системы', 'system');

-- Назначаем все права админу
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions WHERE id NOT IN (SELECT permission_id FROM role_permissions WHERE role_id = 1);
