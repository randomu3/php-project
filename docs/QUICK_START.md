# Быстрый старт

## 1. Установка (5 минут)

```powershell
# Клонировать репозиторий
git clone <repo-url>
cd project

# Запустить Docker
docker-compose -f docker-compose.dev.yml up -d

# Запустить миграции
.\scripts\setup.ps1

# Открыть в браузере
start http://localhost:8080
```

## 2. Создать админа

```powershell
.\scripts\quick-admin-login.ps1
```

Или вручную:

```sql
UPDATE users SET is_admin = 1 WHERE username = 'your_username';
```

## 3. Настроить Email (опционально)

1. Зарегистрироваться на [resend.com](https://resend.com)
2. Получить API ключ
3. Добавить в `docker-compose.dev.yml`:

```yaml
environment:
  RESEND_API_KEY: "re_your_key_here"
```

4. Перезапустить:

```powershell
docker-compose -f docker-compose.dev.yml restart web
```

## 4. Разработка

### Структура файлов

```
src/
├── controllers/     # PHP логика
├── views/          # HTML шаблоны
└── assets/         # CSS и JS
```

### Изменить страницу входа

1. **HTML**: `src/views/login.view.php`
2. **Логика**: `src/controllers/LoginController.php`
3. **JS**: `src/assets/js/app.js`
4. **CSS**: `src/assets/css/style.css`

### Добавить новую страницу

```php
// 1. src/controllers/ProfileController.php
class ProfileController {
    public function index() {
        require __DIR__ . '/../views/profile.view.php';
    }
}

// 2. src/views/profile.view.php
<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/app.js"></script>
</head>
<body>
    <h1>Профиль</h1>
</body>
</html>

// 3. src/profile.php
<?php
require_once __DIR__ . '/controllers/ProfileController.php';
$controller = new ProfileController();
$controller->index();
```

## 5. jQuery примеры

### Валидация формы

```javascript
// Автоматически работает для всех форм
$('form').on('submit', function(e) {
    // Проверка required полей
});
```

### Переключение табов

```javascript
switchTab('database');
```

### Уведомления

```javascript
showNotification('Успешно!', 'success');
showNotification('Ошибка!', 'error');
```

### AJAX запрос

```javascript
$.ajax({
    url: '/api/users',
    method: 'GET',
    success: function(data) {
        console.log(data);
    }
});
```

## 6. Полезные команды

```powershell
# Перезапустить контейнеры
docker-compose -f docker-compose.dev.yml restart

# Посмотреть логи
docker-compose -f docker-compose.dev.yml logs -f web

# Подключиться к БД
.\scripts\db-connect.ps1

# Резервная копия БД
.\scripts\backup.ps1

# Диагностика проблем
.\scripts\diagnose.ps1
```

## 7. Доступ

- **Приложение**: http://localhost:8080
- **Админ-панель**: http://localhost:8080/admin
- **База данных**: localhost:3306

## 8. Документация

- [Структура проекта](PROJECT_STRUCTURE.md)
- [Руководство по jQuery](JQUERY_GUIDE.md)
- [Миграция кода](MIGRATION_GUIDE.md)
- [Настройка Email](EMAIL_SETUP.md)

## 9. Troubleshooting

### Контейнеры не запускаются

```powershell
docker-compose -f docker-compose.dev.yml down
docker-compose -f docker-compose.dev.yml up -d --build
```

### Ошибка подключения к БД

```powershell
# Проверить статус
docker ps

# Проверить логи
docker-compose -f docker-compose.dev.yml logs db
```

### Email не отправляются

1. Проверить `RESEND_API_KEY` в `docker-compose.dev.yml`
2. Проверить логи: `docker-compose logs web`
3. В тестовом режиме письма идут только на `demiz99@mail.ru`

### Страница не обновляется

1. Очистить кэш браузера (Ctrl+Shift+R)
2. Проверить логи: `docker-compose logs -f web`
3. Перезапустить: `docker-compose restart web`

## 10. Следующие шаги

1. ✅ Проект запущен
2. ✅ Админ создан
3. ✅ Email настроен
4. 📖 Изучить [документацию](PROJECT_STRUCTURE.md)
5. 🚀 Начать разработку!

## Помощь

Если что-то не работает:

1. Проверьте документацию в `docs/`
2. Запустите `.\scripts\diagnose.ps1`
3. Проверьте логи: `docker-compose logs -f`
