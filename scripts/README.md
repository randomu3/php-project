# Scripts Directory

Полезные скрипты для управления проектом.

## База данных

### `migrate.ps1`
Запускает миграции базы данных.
```powershell
.\scripts\migrate.ps1
```

### `run-migrations.ps1` / `run-new-migrations.ps1`
Альтернативные скрипты для запуска миграций.

### `db-connect.ps1`
Подключение к MySQL CLI в Docker контейнере.
```powershell
.\scripts\db-connect.ps1
```

### `show-users.cmd`
Показывает список всех пользователей из базы данных.
```cmd
.\scripts\show-users.cmd
```

### `backup.ps1`
Создает резервную копию базы данных.
```powershell
.\scripts\backup.ps1
```

## PWA и иконки

### `generate-pwa-icons.ps1`
Генерирует PWA иконки разных размеров.
```powershell
.\scripts\generate-pwa-icons.ps1
```

### `create-placeholder-icons.ps1`
Создает placeholder иконки для PWA.
```powershell
.\scripts\create-placeholder-icons.ps1
```

### `generate-icons.php`
PHP скрипт для генерации иконок.

## Разработка

### `setup.ps1`
Первоначальная настройка проекта.
```powershell
.\scripts\setup.ps1
```

### `open-admin.ps1`
Открывает админ-панель в браузере.
```powershell
.\scripts\open-admin.ps1
```

### `quick-admin-login.ps1`
Быстрый вход в админ-панель.
```powershell
.\scripts\quick-admin-login.ps1
```

### `test-profile.ps1`
Тестирование профиля пользователя.
```powershell
.\scripts\test-profile.ps1
```

## Email шаблоны

### `update-templates.ps1` / `update-templates.cmd`
Обновление email шаблонов в базе данных.
```powershell
.\scripts\update-templates.ps1
```

## Примечания

- Все скрипты предполагают, что Docker контейнеры запущены
- Для PowerShell скриптов используйте: `.\scripts\script-name.ps1`
- Для CMD скриптов используйте: `.\scripts\script-name.cmd`
