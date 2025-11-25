-- Seed data for admin features
-- Cron jobs, menus, sample pages
-- Encoding: UTF-8

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Примеры cron задач
INSERT IGNORE INTO cron_jobs (name, command, schedule, description, is_active) VALUES
('cleanup_sessions', 'php /var/www/html/cron/cleanup-sessions.php', '0 */6 * * *', 'Очистка устаревших сессий каждые 6 часов', 1),
('process_email_queue', 'php /var/www/html/cron/process-emails.php', '*/5 * * * *', 'Обработка очереди email каждые 5 минут', 1),
('cleanup_logs', 'php /var/www/html/cron/cleanup-logs.php', '0 3 * * *', 'Очистка старых логов в 3:00', 1),
('backup_database', 'php /var/www/html/cron/backup-db.php', '0 2 * * *', 'Резервное копирование БД в 2:00', 0),
('generate_sitemap', 'php /var/www/html/cron/generate-sitemap.php', '0 4 * * 0', 'Генерация sitemap каждое воскресенье', 0),
('send_notifications', 'php /var/www/html/cron/send-notifications.php', '*/10 * * * *', 'Отправка push уведомлений', 1),
('update_stats', 'php /var/www/html/cron/update-stats.php', '0 * * * *', 'Обновление статистики каждый час', 1);

-- Главное меню
INSERT IGNORE INTO cms_menus (id, name, location) VALUES
(1, 'Главное меню', 'header'),
(2, 'Меню футера', 'footer'),
(3, 'Боковое меню', 'sidebar');

-- Пункты главного меню
INSERT IGNORE INTO cms_menu_items (menu_id, title, url, icon, sort_order, is_active) VALUES
(1, 'Главная', '/', 'home', 1, 1),
(1, 'О нас', '/about', 'info', 2, 1),
(1, 'Услуги', '/services', 'briefcase', 3, 1),
(1, 'Блог', '/blog', 'book-open', 4, 1),
(1, 'Контакты', '/contacts', 'mail', 5, 1);

-- Пункты меню футера
INSERT IGNORE INTO cms_menu_items (menu_id, title, url, sort_order, is_active) VALUES
(2, 'Политика конфиденциальности', '/privacy', 1, 1),
(2, 'Условия использования', '/terms', 2, 1),
(2, 'FAQ', '/faq', 3, 1),
(2, 'Карта сайта', '/sitemap', 4, 1);

-- Пункты бокового меню
INSERT IGNORE INTO cms_menu_items (menu_id, title, url, icon, sort_order, is_active) VALUES
(3, 'Профиль', '/profile', 'user', 1, 1),
(3, 'Настройки', '/settings', 'settings', 2, 1),
(3, 'Уведомления', '/notifications', 'bell', 3, 1);

-- Примеры страниц
INSERT IGNORE INTO cms_pages (slug, title, content, meta_title, meta_description, meta_keywords, status, sort_order) VALUES
('about', 'О нас', '<h2>О нашей компании</h2><p>Добро пожаловать! Мы рады видеть вас на нашем сайте.</p><p>Наша команда профессионалов работает для вас с 2020 года.</p><h3>Наши преимущества</h3><ul><li>Опыт работы более 5 лет</li><li>Индивидуальный подход</li><li>Гарантия качества</li></ul>', 'О нас | Наш сайт', 'Информация о нашей компании и команде', 'о нас, компания, команда', 'published', 1),
('contacts', 'Контакты', '<h2>Свяжитесь с нами</h2><p>Email: info@example.com</p><p>Телефон: +7 (999) 123-45-67</p><p>Адрес: г. Москва, ул. Примерная, д. 1</p><h3>Режим работы</h3><p>Пн-Пт: 9:00 - 18:00</p><p>Сб-Вс: выходной</p>', 'Контакты | Наш сайт', 'Контактная информация для связи', 'контакты, телефон, email, адрес', 'published', 2),
('privacy', 'Политика конфиденциальности', '<h2>Политика конфиденциальности</h2><p>Мы заботимся о вашей приватности и защите персональных данных.</p><h3>Сбор информации</h3><p>Мы собираем только необходимую информацию для предоставления услуг.</p><h3>Использование данных</h3><p>Ваши данные используются исключительно для улучшения качества обслуживания.</p>', 'Политика конфиденциальности', 'Политика обработки персональных данных', 'политика, конфиденциальность, данные', 'published', 3),
('terms', 'Условия использования', '<h2>Условия использования</h2><p>Используя наш сайт, вы соглашаетесь с данными условиями.</p><h3>Общие положения</h3><p>Сайт предоставляется "как есть" без каких-либо гарантий.</p><h3>Ограничение ответственности</h3><p>Мы не несем ответственности за любые убытки, связанные с использованием сайта.</p>', 'Условия использования', 'Правила и условия использования сайта', 'условия, правила, использование', 'published', 4),
('services', 'Услуги', '<h2>Наши услуги</h2><p>Мы предлагаем широкий спектр услуг для вашего бизнеса.</p><div class="services-grid"><div class="service"><h3>Веб-разработка</h3><p>Создание современных веб-сайтов и приложений.</p></div><div class="service"><h3>Дизайн</h3><p>UI/UX дизайн и брендинг.</p></div><div class="service"><h3>Маркетинг</h3><p>SEO продвижение и контекстная реклама.</p></div></div>', 'Услуги | Наш сайт', 'Полный спектр услуг для вашего бизнеса', 'услуги, разработка, дизайн, маркетинг', 'published', 5),
('blog', 'Блог', '<h2>Наш блог</h2><p>Полезные статьи и новости из мира технологий.</p>', 'Блог | Наш сайт', 'Статьи и новости', 'блог, статьи, новости', 'published', 6),
('faq', 'Часто задаваемые вопросы', '<h2>FAQ</h2><div class="faq-item"><h3>Как с вами связаться?</h3><p>Вы можете связаться с нами по телефону или email, указанным на странице контактов.</p></div><div class="faq-item"><h3>Какие способы оплаты вы принимаете?</h3><p>Мы принимаем банковские карты, электронные кошельки и банковские переводы.</p></div><div class="faq-item"><h3>Есть ли гарантия на услуги?</h3><p>Да, мы предоставляем гарантию на все наши услуги.</p></div>', 'FAQ | Наш сайт', 'Ответы на часто задаваемые вопросы', 'faq, вопросы, ответы', 'published', 7),
('pricing', 'Цены', '<h2>Наши цены</h2><p>Прозрачное ценообразование без скрытых платежей.</p><div class="pricing-table"><div class="plan"><h3>Базовый</h3><p class="price">9 990 ₽/мес</p><ul><li>До 5 пользователей</li><li>10 ГБ хранилища</li><li>Email поддержка</li></ul></div><div class="plan featured"><h3>Профессиональный</h3><p class="price">19 990 ₽/мес</p><ul><li>До 25 пользователей</li><li>100 ГБ хранилища</li><li>Приоритетная поддержка</li></ul></div><div class="plan"><h3>Корпоративный</h3><p class="price">По запросу</p><ul><li>Неограниченно пользователей</li><li>Неограниченно хранилища</li><li>Персональный менеджер</li></ul></div></div>', 'Цены | Наш сайт', 'Тарифы и цены на услуги', 'цены, тарифы, стоимость', 'published', 8),
('team', 'Команда', '<h2>Наша команда</h2><p>Познакомьтесь с профессионалами, которые работают для вас.</p>', 'Команда | Наш сайт', 'Наша команда профессионалов', 'команда, сотрудники', 'draft', 9);

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

-- Тестовые системные логи
INSERT IGNORE INTO system_logs (level, channel, message, ip_address, url, created_at) VALUES
('info', 'auth', 'User logged in successfully', '192.168.1.1', '/login', NOW() - INTERVAL 1 HOUR),
('info', 'auth', 'User logged out', '192.168.1.1', '/logout', NOW() - INTERVAL 30 MINUTE),
('warning', 'security', 'Failed login attempt', '10.0.0.5', '/login', NOW() - INTERVAL 2 HOUR),
('error', 'app', 'Database connection timeout', '127.0.0.1', '/api/users', NOW() - INTERVAL 3 HOUR),
('info', 'cron', 'Cleanup job completed', NULL, NULL, NOW() - INTERVAL 6 HOUR),
('warning', 'email', 'Email delivery delayed', NULL, NULL, NOW() - INTERVAL 12 HOUR),
('info', 'system', 'Cache cleared successfully', '192.168.1.100', '/admin', NOW() - INTERVAL 1 DAY);

-- Тестовые email в очереди
INSERT IGNORE INTO email_queue (to_email, to_name, subject, body, priority, status, created_at) VALUES
('user1@example.com', 'User One', 'Добро пожаловать!', '<h1>Добро пожаловать на наш сайт!</h1><p>Спасибо за регистрацию.</p>', 1, 'pending', NOW()),
('user2@example.com', 'User Two', 'Новости недели', '<h1>Еженедельная рассылка</h1><p>Самые важные новости за неделю.</p>', 0, 'pending', NOW() - INTERVAL 1 HOUR),
('user3@example.com', 'User Three', 'Специальное предложение', '<h1>Только для вас!</h1><p>Скидка 20% на все услуги.</p>', 2, 'sent', NOW() - INTERVAL 2 HOUR);
