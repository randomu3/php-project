#!/bin/bash
# Скрипт запуска для разработки
# Устанавливает зависимости, запускает миграции и стартует Apache

set -e

echo "🔧 AuraUI Development Server Starting..."
echo ""

# Установка Composer зависимостей если нужно
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
    echo ""
fi

# Запуск миграций и сидов в фоне (не блокируем старт Apache)
echo "🗄️  Running database initialization in background..."
(/usr/local/bin/init-db.sh &) || true

echo ""
echo "🚀 Starting Apache..."
echo "   Server: http://localhost:8080"
echo "   Admin:  http://localhost:8080/admin"
echo ""

# Запуск Apache
exec apache2-foreground
