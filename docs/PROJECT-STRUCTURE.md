# Структура проекта - Полная документация

## 📁 Обзор директорий

```
phpproject/
├── .kiro/                      # Конфигурация Kiro IDE
│   ├── settings/               # Настройки
│   └── steering/               # Правила и руководства
│       ├── tech.md            # Технологический стек
│       ├── structure.md       # Структура проекта
│       └── product.md         # Описание продукта
│
├── database/                   # База данных
│   ├── migrations/            # SQL миграции (001_*.sql)
│   └── seeds/                 # Начальные данные
│
├── docs/                      # Документация
│   ├── PWA-TESTING.md        # Тестирование PWA
│   └── PROJECT-STRUCTURE.md  # Этот файл
│
├── scripts/                   # Утилиты
│   ├── migrate.ps1           # Запуск миграций
│   ├── db-connect.ps1        # Подключение к MySQL
│   ├── backup.ps1            # Резервное копирование
│   ├── show-users.cmd        # Список пользователей
│   └── create-placeholder-icons.ps1  # Создание PWA иконок
│
├── src/                       # Исходный код приложения
│   ├── api/                  # API endpoints
│   │   ├── admin/           # API админ-панели
│   │   ├── notifications/   # API уведомлений
│   │   └── profile/         # API профиля
│   │
│   ├── assets/              # Статические ресурсы
│   │   ├── css/            # Стили
│   │   │   ├── style.css
│   │   │   └── loader.css
│   │   ├── js/             # JavaScript
│   │   │   ├── app.js
│   │   │   ├── loader.js
│   │   │   ├── lazy-load.js
│   │   │   └── sw-register.js
│   │   └── images/         # Изображения и PWA иконки
│   │
│   ├── controllers/         # Контроллеры (MVC)
│   │   ├── AdminController.php
│   │   ├── LoginController.php
│   │   ├── RegisterController.php
│   │   ├── ProfileController.php
│   │   ├── NotificationsController.php
│   │   ├── ForgotPasswordController.php
│   │   └── ResetPasswordController.php
│   │
│   ├── core/               # Системные классы
│   │   ├── Cache.php              # Кеширование (Redis/File)
│   │   ├── QueryCache.php         # Кеш запросов БД
│   │   ├── Logger.php             # Логирование
│   │   ├── Paginator.php          # Пагинация
│   │   ├── RateLimiter.php        # Ограничение запросов
│   │   └── BatchProcessor.php     # Пакетная обработка
│   │
│   ├── helpers/            # Вспомогательные классы
│   │   ├── ActivityLogger.php     # Логи активности
│   │   ├── NotificationManager.php # Уведомления
│   │   ├── ImageUploader.php      # Загрузка изображений
│   │   ├── CacheHelper.php        # Хелперы кеша
│   │   ├── CDN.php               # Интеграция CDN
│   │   ├── ImageOptimizer.php    # Оптимизация изображений
│   │   └── Minifier.php          # Минификация
│   │
│   ├── templates/          # Общие шаблоны
│   │   ├── header.php     # Шапка
│   │   └── footer.php     # Подвал
│   │
│   ├── uploads/           # Загруженные файлы
│   │   └── avatars/      # Аватары пользователей
│   │
│   ├── views/            # Представления (HTML)
│   │   ├── partials/    # Компоненты
│   │   ├── admin.view.php
│   │   ├── login.view.php
│   │   ├── register.view.php
│   │   ├── profile.view.php
│   │   ├── notifications.view.php
│   │   ├── forgot_password.view.php
│   │   └── reset_password.view.php
│   │
│   ├── vendor/           # Composer зависимости
│   │
│   ├── .htaccess        # Apache конфигурация
│   ├── config.php       # Главный конфиг
│   ├── composer.json    # Зависимости PHP
│   ├── manifest.json    # PWA манифест
│   ├── service-worker.js # Service Worker
│   ├── offline.html     # Офлайн страница
│   │
│   └── *.php           # Точки входа
│       ├── index.php
│       ├── login.php
│       ├── register.php
│       ├── profile.php
│       ├── admin.php
│       ├── notifications.php
│       ├── forgot_password.php
│       ├── reset_password.php
│       ├── logout.php
│       ├── email.php
│       └── pwa-test.php
│
├── docker-compose.yml         # Docker продакшен
├── docker-compose.dev.yml     # Docker разработка
├── Dockerfile                 # Docker образ продакшен
└── Dockerfile.dev            # Docker образ разработка
```

## 🎯 Назначение директорий

### `/src/core/` - Системные классы

**Критерии размещения:**
- Базовая функциональность системы
- Используется во многих местах
- Не зависит от бизнес-логики

**Примеры:**
- Кеширование
- Логирование
- Пагинация
- Ограничение запросов

### `/src/helpers/` - Вспомогательные классы

**Критерии размещения:**
- Специфичная функциональность
- Может зависеть от бизнес-логики
- Расширяет возможности системы

**Примеры:**
- Работа с изображениями
- Уведомления
- CDN интеграция
- Оптимизация

### `/src/controllers/` - Контроллеры

**Критерии размещения:**
- Обработка HTTP запросов
- Бизнес-логика
- Взаимодействие с моделями и представлениями

**Соглашения:**
- Имя: `PascalCase` + `Controller` суффикс
- Метод `index()` для GET запросов
- Приватные методы для POST/PUT/DELETE

### `/src/views/` - Представления

**Критерии размещения:**
- HTML шаблоны
- Минимум PHP (только вывод)
- Переиспользуемые компоненты в `/partials/`

**Соглашения:**
- Имя: `lowercase` + `.view.php` суффикс
- Всегда экранировать вывод: `htmlspecialchars()`

## 🔧 Ключевые файлы

### `config.php` - Главная конфигурация

```php
// Порядок загрузки:
1. Composer autoload
2. Core классы (из /core/)
3. Helper классы (из /helpers/)
4. Настройки сессии
5. Подключение к БД
6. Константы
```

### `service-worker.js` - PWA Service Worker

```javascript
// Функции:
- Офлайн режим
- Кеширование (Network First)
- Push уведомления
- Фоновая синхронизация
```

### `manifest.json` - Web App Manifest

```json
// Содержит:
- Метаданные приложения
- 8 PWA иконок (72px - 512px)
- Настройки отображения
- Shortcuts
```

### `.htaccess` - Apache конфигурация

```apache
// Функции:
- URL rewriting (убирает .php)
- Кеширование статики
- Gzip сжатие
- Security headers
```

## 📝 Правила размещения кода

### 1. Новый контроллер

```
1. Создать: src/controllers/NewController.php
2. Создать: src/views/new.view.php
3. Создать: src/new.php (точка входа)
4. Обновить: src/.htaccess (если нужны clean URLs)
```

### 2. Новый системный класс

```
1. Создать: src/core/NewClass.php
2. Добавить в: src/config.php (require_once)
3. Использовать везде через: new NewClass()
```

### 3. Новый helper класс

```
1. Создать: src/helpers/NewHelper.php
2. Добавить в: src/config.php (require_once)
3. Использовать: NewHelper::method()
```

### 4. Новая таблица БД

```
1. Создать: database/migrations/XXX_create_table.sql
2. Запустить: .\scripts\migrate.ps1
```

### 5. Новый API endpoint

```
1. Создать: src/api/category/endpoint.php
2. Использовать: POST/GET запросы
3. Возвращать: JSON ответы
```

## 🚀 Точки входа

| URL | Файл | Контроллер | Описание |
|-----|------|------------|----------|
| `/` | `index.php` | - | Главная страница |
| `/login` | `login.php` | `LoginController` | Авторизация |
| `/register` | `register.php` | `RegisterController` | Регистрация |
| `/profile` | `profile.php` | `ProfileController` | Профиль |
| `/admin` | `admin.php` | `AdminController` | Админ-панель |
| `/notifications` | `notifications.php` | `NotificationsController` | Уведомления |
| `/forgot-password` | `forgot_password.php` | `ForgotPasswordController` | Восстановление |
| `/reset-password` | `reset_password.php` | `ResetPasswordController` | Сброс пароля |
| `/logout` | `logout.php` | - | Выход |
| `/pwa-test` | `pwa-test.php` | - | Тест PWA |

## 🔐 Безопасность

### Защищенные директории

```apache
/src/uploads/avatars/.htaccess  # Запрет выполнения PHP
/src/vendor/                     # Composer пакеты
/database/                       # Вне web root
/scripts/                        # Вне web root
```

### Проверки безопасности

```php
// В каждом контроллере:
- verifyCSRFToken()      # CSRF защита
- sanitizeInput()        # Очистка ввода
- htmlspecialchars()     # XSS защита
- Prepared statements    # SQL injection
- requireLogin()         # Авторизация
- requireAdmin()         # Права админа
```

## 📊 Производительность

### Кеширование

```
1. Redis (если доступен)
2. File cache (fallback)
3. Query cache (БД запросы)
4. Browser cache (статика)
5. Service Worker cache (PWA)
```

### Оптимизация

```
1. Gzip сжатие (.htaccess)
2. Browser caching (1 год для статики)
3. Lazy loading (изображения)
4. Minification (CSS/JS)
5. WebP конвертация (изображения)
6. Connection pooling (БД)
7. Batch processing (массовые операции)
```

## 🧪 Тестирование

### PWA тест

```
URL: http://localhost:8080/pwa-test
Проверяет:
- Service Worker статус
- Размер кеша
- Манифест
- Установку PWA
- Офлайн режим
```

### Проверка структуры

```powershell
# Проверить что все классы на месте
Get-ChildItem src/core -Name
Get-ChildItem src/helpers -Name
Get-ChildItem src/controllers -Name
```

## 📚 Дополнительная документация

- `docs/PWA-TESTING.md` - Тестирование PWA
- `.kiro/steering/tech.md` - Технологический стек
- `.kiro/steering/structure.md` - Архитектура
- `.kiro/steering/product.md` - Описание продукта

## 🔄 Миграция классов

Если класс находится не в том месте:

```powershell
# Переместить файл
Move-Item "src/ClassName.php" "src/core/ClassName.php"

# Обновить require в config.php
# Было: require_once __DIR__ . '/ClassName.php';
# Стало: require_once __DIR__ . '/core/ClassName.php';
```

## ✅ Чеклист правильной структуры

- [ ] Все контроллеры в `/src/controllers/`
- [ ] Все представления в `/src/views/`
- [ ] Системные классы в `/src/core/`
- [ ] Вспомогательные классы в `/src/helpers/`
- [ ] API endpoints в `/src/api/`
- [ ] Статика в `/src/assets/`
- [ ] Загрузки в `/src/uploads/`
- [ ] Миграции в `/database/migrations/`
- [ ] Скрипты в `/scripts/`
- [ ] Документация в `/docs/`
- [ ] Все классы подключены в `config.php`
- [ ] Service Worker работает
- [ ] PWA иконки на месте
