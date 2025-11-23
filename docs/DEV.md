# Инструкция для разработки

## Запуск в режиме разработки

```bash
docker-compose -f docker-compose.dev.yml up -d
```

## Hot Reload

Все изменения в папке `src/` применяются **мгновенно** без перезапуска контейнера:

- Изменили PHP файл → просто обновите страницу в браузере
- Добавили новый файл → сразу доступен
- Изменили CSS/HTML → мгновенно видно

## Просмотр логов

```bash
# Все логи
docker-compose -f docker-compose.dev.yml logs -f

# Только web сервер
docker-compose -f docker-compose.dev.yml logs -f web

# Только база данных
docker-compose -f docker-compose.dev.yml logs -f db
```

## Установка новых зависимостей

```bash
# Войти в контейнер
docker-compose -f docker-compose.dev.yml exec web bash

# Установить пакет
composer require package/name

# Выйти
exit
```

## Остановка

```bash
docker-compose -f docker-compose.dev.yml down
```

## Полная очистка (включая БД)

```bash
docker-compose -f docker-compose.dev.yml down -v
```

## Отладка

В режиме разработки включены:
- Все PHP ошибки и предупреждения
- Детальные логи Apache
- Автоматический перезапуск при падении

## Подключение к БД

Используйте любой MySQL клиент:
- Host: `localhost`
- Port: `3306`
- User: `app_user`
- Password: `app_password`
- Database: `app_db`
