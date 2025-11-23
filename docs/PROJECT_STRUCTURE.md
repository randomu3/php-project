# Структура проекта

## Обзор

Проект реструктурирован с использованием MVC-подобной архитектуры, jQuery для фронтенда и четкого разделения логики.

## Структура папок

```
project/
├── database/                    # База данных
│   ├── migrations/             # SQL миграции
│   │   ├── 001_create_users.sql
│   │   ├── 002_create_password_resets.sql
│   │   └── 003_create_email_templates.sql
│   └── seeds/                  # Начальные данные
│       └── default_templates.sql
│
├── docs/                       # Документация
│   ├── PROJECT_STRUCTURE.md   # Этот файл
│   ├── DATABASE.md            # Схема БД
│   ├── EMAIL_SETUP.md         # Настройка email
│   └── ...                    # Другие документы
│
├── scripts/                    # Скрипты автоматизации
│   ├── setup.ps1              # Первоначальная настройка
│   ├── migrate.ps1            # Запуск миграций
│   ├── backup.ps1             # Резервное копирование
│   └── ...                    # Другие скрипты
│
└── src/                        # Исходный код приложения
    ├── assets/                 # Статические ресурсы
    │   ├── css/
    │   │   └── style.css      # Основные стили
    │   └── js/
    │       └── app.js         # jQuery логика
    │
    ├── controllers/            # Контроллеры (бизнес-логика)
    │   ├── LoginController.php
    │   ├── RegisterController.php
    │   └── AdminController.php
    │
    ├── views/                  # Представления (HTML)
    │   ├── login.view.php
    │   ├── register.view.php
    │   ├── admin.view.php
    │   ├── access_denied.view.php
    │   └── partials/          # Переиспользуемые части
    │       ├── users_table.php
    │       ├── tokens_table.php
    │       ├── templates_section.php
    │       └── email_sender.php
    │
    ├── config.php              # Конфигурация и утилиты
    ├── email.php               # Email функции
    │
    └── *.php                   # Точки входа (роутинг)
        ├── index.php
        ├── login.php
        ├── register.php
        ├── admin.php
        └── ...
```

## Архитектура

### 1. Контроллеры (Controllers)

Контроллеры содержат бизнес-логику и обработку запросов:

```php
// src/controllers/LoginController.php
class LoginController {
    public function index() {
        // Обработка GET запроса
    }
    
    private function handleLogin() {
        // Обработка POST запроса
    }
}
```

### 2. Представления (Views)

Views содержат только HTML и минимальный PHP для вывода данных:

```php
// src/views/login.view.php
<!DOCTYPE html>
<html>
<head>...</head>
<body>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <!-- HTML форма -->
    </form>
</body>
</html>
```

### 3. Точки входа (Entry Points)

Файлы в корне `src/` служат роутерами:

```php
// src/login.php
<?php
require_once __DIR__ . '/controllers/LoginController.php';

$controller = new LoginController();
$controller->index();
exit;
```

### 4. Frontend (jQuery)

Вся клиентская логика в `src/assets/js/app.js`:

```javascript
$(document).ready(function() {
    // Валидация форм
    // Переключение табов
    // AJAX запросы
});
```

## Преимущества новой структуры

1. **Разделение ответственности**: HTML, PHP логика и JS разделены
2. **Переиспользование кода**: Partials для повторяющихся элементов
3. **Легкая поддержка**: Понятная структура файлов
4. **Масштабируемость**: Легко добавлять новые контроллеры/views
5. **jQuery**: Меньше кода, больше функциональности

## Работа с проектом

### Добавление новой страницы

1. Создать контроллер: `src/controllers/NewController.php`
2. Создать view: `src/views/new.view.php`
3. Создать точку входа: `src/new.php`
4. Добавить JS логику в `src/assets/js/app.js` (если нужно)

### Работа с миграциями

```powershell
# Запустить все миграции
.\scripts\migrate.ps1

# Создать новую миграцию
# Добавить файл в database/migrations/
# Формат: XXX_description.sql
```

### Работа с шаблонами email

Шаблоны хранятся в БД и управляются через админ-панель.
Начальные шаблоны: `database/seeds/default_templates.sql`

## Технологии

- **Backend**: PHP 8.1+, PDO, Argon2ID
- **Frontend**: jQuery 3.7.1, Tailwind CSS, Lucide Icons
- **Database**: MySQL 8.0
- **Email**: Resend API
- **Docker**: Для разработки и продакшена
