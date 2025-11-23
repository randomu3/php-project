# Скрипт для запуска новых миграций

Write-Host "=== Запуск новых миграций ===" -ForegroundColor Cyan
Write-Host ""

$migrations = @(
    "005_create_roles_system.sql",
    "006_create_activity_logs.sql",
    "007_create_notifications.sql"
)

foreach ($migration in $migrations) {
    Write-Host "Выполнение миграции: $migration" -ForegroundColor Yellow
    
    $result = docker-compose -f docker-compose.dev.yml exec -T db mysql -uapp_user -papp_password app_db < "database/migrations/$migration" 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ $migration выполнена успешно" -ForegroundColor Green
    } else {
        Write-Host "✗ Ошибка при выполнении $migration" -ForegroundColor Red
        Write-Host $result
    }
    
    Write-Host ""
}

Write-Host "=== Миграции завершены ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "Добавлено:" -ForegroundColor Green
Write-Host "✓ Система ролей и прав доступа" -ForegroundColor Green
Write-Host "✓ Логирование действий" -ForegroundColor Green
Write-Host "✓ Уведомления в системе" -ForegroundColor Green
Write-Host ""
Write-Host "Для проверки откройте:" -ForegroundColor Yellow
Write-Host "- http://localhost:8080/notifications - Страница уведомлений" -ForegroundColor Gray
Write-Host "- Колокольчик в навигации - Быстрый доступ к уведомлениям" -ForegroundColor Gray
