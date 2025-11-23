# Руководство по миграции на новую структуру

## Что изменилось

### Старая структура
```
src/
├── login.php (300+ строк PHP + HTML)
├── register.php (300+ строк PHP + HTML)
├── admin.php (500+ строк PHP + HTML)
└── assets/
    ├── css/style.css
    └── js/app.js (vanilla JS)
```

### Новая структура
```
src/
├── controllers/          # Бизнес-логика
│   ├── LoginController.php
│   ├── RegisterController.php
│   └── AdminController.php
├── views/               # HTML шаблоны
│   ├── login.view.php
│   ├── register.view.php
│   ├── admin.view.php
│   └── partials/
├── assets/
│   ├── css/style.css    # Обновлен
│   └── js/app.js        # jQuery
└── *.php                # Роутеры (3-5 строк)
```

## Изменения в файлах

### 1. login.php

**Было:**
```php
<?php
require_once 'config.php';

// 50+ строк логики входа
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обработка формы
}
?>
<!DOCTYPE html>
<html>
<!-- 200+ строк HTML -->
</html>
```

**Стало:**
```php
<?php
require_once __DIR__ . '/controllers/LoginController.php';

$controller = new LoginController();
$controller->index();
exit;
```

### 2. register.php

**Было:** 300+ строк PHP + HTML

**Стало:** 5 строк (роутер)

### 3. admin.php

**Было:** 550+ строк PHP + HTML + JavaScript

**Стало:** 5 строк (роутер)

### 4. assets/js/app.js

**Было (Vanilla JS):**
```javascript
document.getElementById('btn').addEventListener('click', function() {
    document.getElementById('tab').classList.add('active');
});
```

**Стало (jQuery):**
```javascript
$('#btn').on('click', function() {
    $('#tab').addClass('active');
});
```

## Обратная совместимость

✅ Все URL остались прежними:
- `/login` → работает
- `/register` → работает
- `/admin` → работает

✅ Все функции работают:
- Вход/регистрация
- Сброс пароля
- Админ-панель
- Email уведомления

## Что нужно обновить

### Если вы модифицировали код

#### 1. Логика входа/регистрации

**Где было:** `src/login.php` (внутри файла)

**Где теперь:** `src/controllers/LoginController.php`

```php
class LoginController {
    private function handleLogin() {
        // Ваша логика здесь
    }
}
```

#### 2. HTML шаблоны

**Где было:** `src/login.php` (внизу файла)

**Где теперь:** `src/views/login.view.php`

```php
<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <!-- Ваш HTML -->
</body>
</html>
```

#### 3. JavaScript логика

**Где было:** Inline `<script>` в каждом файле

**Где теперь:** `src/assets/js/app.js`

```javascript
$(document).ready(function() {
    // Ваша логика
});
```

## Преимущества новой структуры

### 1. Меньше кода

**Было:**
```javascript
// 10 строк для переключения таба
const tabs = document.querySelectorAll('.tab-content');
tabs.forEach(tab => tab.classList.add('hidden'));
document.getElementById('tab-' + tabId).classList.remove('hidden');
// и т.д.
```

**Стало:**
```javascript
// 1 строка
switchTab('database');
```

### 2. Легче поддерживать

- Логика отделена от HTML
- Каждый файл имеет одну ответственность
- Легко найти нужный код

### 3. Переиспользование

```php
// Использовать один partial в разных местах
<?php require __DIR__ . '/partials/users_table.php'; ?>
```

### 4. Тестирование

```php
// Легко тестировать контроллеры
$controller = new LoginController();
$result = $controller->handleLogin();
```

## Как работать с новой структурой

### Изменить логику входа

1. Открыть `src/controllers/LoginController.php`
2. Найти метод `handleLogin()`
3. Внести изменения
4. Сохранить

### Изменить HTML формы входа

1. Открыть `src/views/login.view.php`
2. Найти `<form>`
3. Внести изменения
4. Сохранить

### Добавить JavaScript функцию

1. Открыть `src/assets/js/app.js`
2. Добавить функцию:
```javascript
function myNewFunction() {
    // Ваш код
}
```
3. Использовать в HTML:
```html
<button onclick="myNewFunction()">Кнопка</button>
```

### Изменить стили

1. Открыть `src/assets/css/style.css`
2. Добавить/изменить CSS
3. Сохранить

## Миграция кастомного кода

### Если вы добавили свои функции

**Было в login.php:**
```php
function myCustomFunction() {
    // код
}
```

**Переместить в:**
- `src/config.php` (если общая функция)
- `src/controllers/LoginController.php` (если специфичная для входа)

### Если вы добавили свой JavaScript

**Было в login.php:**
```html
<script>
function myFunction() {
    // код
}
</script>
```

**Переместить в:**
`src/assets/js/app.js`

```javascript
function myFunction() {
    // код
}
```

## Проверка после миграции

### 1. Запустить Docker

```powershell
docker-compose -f docker-compose.dev.yml up -d
```

### 2. Проверить страницы

- http://localhost:8080/login
- http://localhost:8080/register
- http://localhost:8080/admin

### 3. Проверить функциональность

- ✅ Вход работает
- ✅ Регистрация работает
- ✅ Админ-панель работает
- ✅ Табы переключаются
- ✅ Формы валидируются

### 4. Проверить консоль браузера

Открыть DevTools (F12) → Console

Не должно быть ошибок JavaScript.

## Откат (если нужно)

Если что-то пошло не так, старые файлы можно восстановить из Git:

```bash
git checkout HEAD~1 src/login.php
git checkout HEAD~1 src/register.php
git checkout HEAD~1 src/admin.php
```

## Поддержка

Если возникли проблемы:

1. Проверьте [docs/PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md)
2. Проверьте [docs/JQUERY_GUIDE.md](JQUERY_GUIDE.md)
3. Запустите диагностику: `.\scripts\diagnose.ps1`
4. Проверьте логи: `docker-compose logs -f web`

## FAQ

### Q: Почему jQuery, а не React/Vue?

A: jQuery проще для PHP проектов, меньше настройки, быстрее старт.

### Q: Можно ли использовать старые файлы?

A: Да, но новая структура удобнее для поддержки.

### Q: Как добавить новую страницу?

A: См. [docs/PROJECT_STRUCTURE.md](PROJECT_STRUCTURE.md) → "Добавление новой страницы"

### Q: Где хранятся сессии?

A: В `$_SESSION`, как и раньше. Логика в `src/config.php`.

### Q: Изменилась ли безопасность?

A: Нет, все меры безопасности сохранены (CSRF, Argon2ID, брутфорс защита).
