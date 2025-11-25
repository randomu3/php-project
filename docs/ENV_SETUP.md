# Настройка переменных окружения

## Быстрый старт

1. Скопируйте файл примера:
```bash
cp .env.example .env
```

2. Отредактируйте `.env` и укажите свои значения:
```bash
# Обязательные переменные
DB_PASS=your_secure_db_password
MYSQL_ROOT_PASSWORD=your_secure_root_password
RESEND_API_KEY=re_your_actual_api_key
```

3. Запустите проект:
```bash
# Для разработки
docker-compose -f docker-compose.dev.yml up -d

# Для продакшена
docker-compose up -d
```

## Переменные окружения

| Переменная | Описание | По умолчанию |
|------------|----------|--------------|
| `DB_HOST` | Хост базы данных | `db` |
| `DB_NAME` | Имя базы данных | `app_db` |
| `DB_USER` | Пользователь БД | `app_user` |
| `DB_PASS` | **Пароль БД** | - |
| `MYSQL_ROOT_PASSWORD` | **Root пароль MySQL** | - |
| `REDIS_HOST` | Хост Redis | `redis` |
| `REDIS_PORT` | Порт Redis | `6379` |
| `RESEND_API_KEY` | **API ключ Resend** | - |
| `FROM_EMAIL` | Email отправителя | `onboarding@resend.dev` |
| `FROM_NAME` | Имя отправителя | `My Site` |
| `CDN_ENABLED` | Включить CDN | `false` |
| `CDN_URL` | URL CDN | - |

**Жирным** выделены обязательные секретные переменные.

## Безопасность

- Файл `.env` добавлен в `.gitignore` и не попадает в репозиторий
- Используйте `.env.example` как шаблон (без реальных секретов)
- Для продакшена используйте надежные пароли (минимум 16 символов)
- Регулярно ротируйте API ключи

## Получение Resend API Key

1. Зарегистрируйтесь на [resend.com](https://resend.com)
2. Перейдите в API Keys
3. Создайте новый ключ
4. Скопируйте в `RESEND_API_KEY`
