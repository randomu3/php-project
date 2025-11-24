---
inclusion: always
---

# Структура проекта

## Директории

### `/src/controllers/`
Контроллеры приложения (MVC pattern)
- `AdminController.php` - Админ-панель
- `LoginController.php` - Авторизация
- `RegisterController.php` - Регистрация
- `ProfileController.php` - Профиль пользователя
- `NotificationsController.php` - Уведомления
- `ForgotPasswordController.php` - Восстановление пароля
- `ResetPasswordController.php` - Сброс пароля

### `/src/views/`
Представления (HTML шаблоны)
- `*.view.php` - Основные страницы
- `/partials/` - Переиспользуемые компоненты

### `/src/core/`
Основные системные классы
- `Cache.php` - Кеширование (Redis/File)
- `QueryCache.php` - Кеширование запросов к БД
- `Logger.php` - Логирование
- `Paginator.php` - Пагинация
- `RateLimiter.php` - Ограничение частоты запросов
- `BatchProcessor.php` - Пакетная обработка данных

### `/src/helpers/`
Вспомогательные классы
- `ActivityLogger.php` - Логирование активности пользователей
- `NotificationManager.php` - Управление уведомлениями
- `ImageUploader.php` - Загрузка изображений
- `CacheHelper.php` - Вспомогательные функции кеширования
- `CDN.php` - Интеграция с CDN
- `ImageOptimizer.php` - Оптимизация изображений (WebP)
- `Minifier.php` - Минификация CSS/JS/HTML

### `/src/templates/`
Общие шаблоны
- `header.php` - Шапка сайта
- `footer.php` - Подвал сайта

### `/src/assets/`
Статические ресурсы
- `/css/` - Стили
- `/js/` - JavaScript файлы
- `/images/` - Изображения и иконки

### `/src/api/`
API endpoints
- `/admin/` - API для админ-панели
- `/notifications/` - API для уведомлений
- `/profile/` - API для профиля

### `/src/uploads/`
Загруженные файлы
- `/avatars/` - Аватары пользователей

### `/database/`
База данных
- `/migrations/` - Миграции (SQL файлы)
- `/seeds/` - Начальные данные

### `/scripts/`
Утилиты и скрипты
- `migrate.ps1` - Запуск миграций
- `db-connect.ps1` - Подключение к MySQL
- `backup.ps1` - Резервное копирование
- `show-users.cmd` - Список пользователей
- `create-placeholder-icons.ps1` - Создание PWA иконок

## Ключевые файлы

### `/src/config.php`
Главный конфигурационный файл
- Подключение к БД
- Настройки безопасности
- Подключение всех классов
- Константы приложения

### `/src/service-worker.js`
Service Worker для PWA
- Офлайн режим
- Кеширование ресурсов
- Push уведомления

### `/src/manifest.json`
Web App Manifest для PWA
- Метаданные приложения
- Иконки
- Настройки отображения

### `/src/.htaccess`
Apache конфигурация
- URL rewriting
- Кеширование
- Сжатие
- Безопасность

## Точки входа

- `/src/index.php` - Главная страница
- `/src/login.php` - Авторизация
- `/src/register.php` - Регистрация
- `/src/profile.php` - Профиль
- `/src/admin.php` - Админ-панель
- `/src/notifications.php` - Уведомления
- `/src/forgot_password.php` - Восстановление пароля
- `/src/reset_password.php` - Сброс пароля
- `/src/logout.php` - Выход
- `/src/pwa-test.php` - Тест PWA функций

## Правила размещения

1. **Контроллеры** → `/src/controllers/`
2. **Системные классы** → `/src/core/`
3. **Вспомогательные классы** → `/src/helpers/`
4. **Представления** → `/src/views/`
5. **API** → `/src/api/`
6. **Статика** → `/src/assets/`

## Автозагрузка

Классы подключаются в `config.php` в следующем порядке:
1. Composer autoload
2. Core классы
3. Helper классы
4. Инициализация CDN

## PWA структура

- `/src/service-worker.js` - Service Worker
- `/src/assets/js/sw-register.js` - Регистрация SW
- `/src/manifest.json` - Web App Manifest
- `/src/offline.html` - Офлайн страница
- `/src/assets/images/icon-*.png` - PWA иконки
