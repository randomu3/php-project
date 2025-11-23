# Скрипт для запуска SQL миграций

Write-Host "=== Запуск миграций базы данных ===" -ForegroundColor Cyan

# Проверка Docker
if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "Ошибка: Docker не установлен!" -ForegroundColor Red
    exit 1
}

# Проверка контейнера
$container = docker ps --filter "name=php-auth-app" --format "{{.Names}}"
if (-not $container) {
    Write-Host "Ошибка: Контейнер не запущен. Запустите: docker-compose up -d" -ForegroundColor Red
    exit 1
}

# Путь к миграциям
$migrationsPath = "database/migrations"

if (-not (Test-Path $migrationsPath)) {
    Write-Host "Ошибка: Папка миграций не найдена!" -ForegroundColor Red
    exit 1
}

# Получить все SQL файлы
$migrations = Get-ChildItem -Path $migrationsPath -Filter "*.sql" | Sort-Object Name

if ($migrations.Count -eq 0) {
    Write-Host "Миграции не найдены." -ForegroundColor Yellow
    exit 0
}

Write-Host "Найдено миграций: $($migrations.Count)" -ForegroundColor Green

# Запустить каждую миграцию
foreach ($migration in $migrations) {
    Write-Host "`nЗапуск: $($migration.Name)" -ForegroundColor Yellow
    
    $sql = Get-Content $migration.FullName -Raw
    
    # Выполнить SQL через Docker
    $result = docker exec -i php-auth-db mysql -uroot -prootpassword auth_db -e $sql 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ $($migration.Name) выполнена успешно" -ForegroundColor Green
    } else {
        Write-Host "✗ Ошибка в $($migration.Name):" -ForegroundColor Red
        Write-Host $result -ForegroundColor Red
    }
}

Write-Host "`n=== Миграции завершены ===" -ForegroundColor Cyan
