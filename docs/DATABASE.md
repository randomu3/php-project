# 🗄️ Работа с базой данных

## 🌐 Веб-интерфейс (самый простой способ)

Откройте в браузере: **http://localhost:8080/admin**

Здесь вы увидите:
- ✅ Всех пользователей
- ✅ Токены восстановления пароля
- ✅ Статистику
- ✅ Статусы (активен/заблокирован/использован)

## 💻 Командная строка

### Показать всех пользователей:
```bash
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "SELECT * FROM users;"
```

### Показать токены восстановления:
```bash
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "SELECT * FROM password_resets;"
```

### Удалить пользователя:
```bash
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "DELETE FROM users WHERE username='testuser';"
```

### Очистить все токены:
```bash
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "DELETE FROM password_resets;"
```

## 🖥️ GUI клиенты (для удобной работы)

### Вариант 1: MySQL Workbench (рекомендуется)
1. Скачайте: https://dev.mysql.com/downloads/workbench/
2. Установите
3. Создайте подключение:
   - Host: `localhost`
   - Port: `3306`
   - Username: `app_user`
   - Password: `app_password`
   - Database: `app_db`

### Вариант 2: DBeaver (универсальный)
1. Скачайте: https://dbeaver.io/download/
2. Установите
3. Создайте подключение MySQL с теми же параметрами

### Вариант 3: HeidiSQL (легкий)
1. Скачайте: https://www.heidisql.com/download.php
2. Установите
3. Создайте подключение с теми же параметрами

### Вариант 4: phpMyAdmin (веб-интерфейс)
Добавьте в `docker-compose.dev.yml`:
```yaml
phpmyadmin:
  image: phpmyadmin:latest
  ports:
    - "8081:80"
  environment:
    PMA_HOST: db
    PMA_USER: app_user
    PMA_PASSWORD: app_password
  depends_on:
    - db
```

Затем: http://localhost:8081

## 📊 Данные подключения

```
Host: localhost
Port: 3306
Username: app_user
Password: app_password
Database: app_db
```

## 🔍 Полезные запросы

### Найти пользователя по email:
```sql
SELECT * FROM users WHERE email = 'demiz99@mail.ru';
```

### Показать последние 5 регистраций:
```sql
SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 5;
```

### Показать активные токены восстановления:
```sql
SELECT u.username, u.email, pr.token, pr.expires_at 
FROM password_resets pr 
JOIN users u ON pr.user_id = u.id 
WHERE pr.used = FALSE AND pr.expires_at > NOW();
```

### Разблокировать пользователя:
```sql
UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE username = 'demiz99';
```

## 🛠️ Скрипты

Создан скрипт `show-users.cmd` для быстрого просмотра:
```bash
show-users.cmd
```

## 📝 Структура таблиц

### Таблица `users`:
- `id` - ID пользователя
- `username` - Имя пользователя (уникальное)
- `email` - Email (уникальный)
- `password_hash` - Хеш пароля (Argon2ID)
- `created_at` - Дата регистрации
- `last_login` - Последний вход
- `failed_attempts` - Количество неудачных попыток входа
- `locked_until` - До какого времени заблокирован

### Таблица `password_resets`:
- `id` - ID токена
- `user_id` - ID пользователя
- `token` - Токен восстановления (64 символа)
- `created_at` - Когда создан
- `expires_at` - Когда истекает (1 час)
- `used` - Использован ли (TRUE/FALSE)

## 🎯 Текущие пользователи

Сейчас в БД:
- `testuser` - test@example.com
- `testuser2` - test2@example.com
- `testuser3` - test3@example.com
- `testuser4` - test4@example.com (пароль: testpass123)
- `demiz99` - demiz99@mail.ru (пароль: SecurePass123!)
