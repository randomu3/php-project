#!/bin/bash
# Скрипт инициализации базы данных для разработки
# Запускается автоматически при старте контейнера

set -e

echo "🚀 Starting database initialization..."

# Ждем пока MySQL будет готов
echo "⏳ Waiting for MySQL to be ready..."
max_tries=30
counter=0

while ! mysqladmin ping -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" --silent 2>/dev/null; do
    counter=$((counter + 1))
    if [ $counter -gt $max_tries ]; then
        echo "❌ MySQL is not available after $max_tries attempts"
        exit 1
    fi
    echo "   Attempt $counter/$max_tries..."
    sleep 2
done

echo "✅ MySQL is ready!"

# Директории
MIGRATIONS_DIR="/var/www/database/migrations"
SEEDS_DIR="/var/www/database/seeds"

# Запускаем миграции
echo ""
echo "📦 Running migrations..."
if [ -d "$MIGRATIONS_DIR" ]; then
    for file in $(ls -v $MIGRATIONS_DIR/*.sql 2>/dev/null); do
        filename=$(basename "$file")
        echo "   → $filename"
        mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$file" 2>/dev/null || true
    done
    echo "✅ Migrations completed!"
else
    echo "⚠️  No migrations directory found"
fi

# Запускаем сиды (только в dev режиме)
if [ "$APP_ENV" = "development" ] || [ "$APP_ENV" = "dev" ] || [ -z "$APP_ENV" ]; then
    echo ""
    echo "🌱 Running seeds (development mode)..."
    if [ -d "$SEEDS_DIR" ]; then
        for file in $(ls -v $SEEDS_DIR/*.sql 2>/dev/null); do
            filename=$(basename "$file")
            echo "   → $filename"
            mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$file" 2>/dev/null || true
        done
        echo "✅ Seeds completed!"
    else
        echo "⚠️  No seeds directory found"
    fi
fi

echo ""
echo "🎉 Database initialization completed!"
echo ""
