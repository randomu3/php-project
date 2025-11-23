# Скрипт для запуска миграций

Write-Host "Running database migrations..." -ForegroundColor Cyan
Write-Host ""

$migrations = Get-ChildItem -Path "database/migrations" -Filter "*.sql" | Sort-Object Name

foreach ($migration in $migrations) {
    Write-Host "Applying: $($migration.Name)" -ForegroundColor Yellow
    Get-Content $migration.FullName | docker-compose -f docker-compose.dev.yml exec -T db mysql -uapp_user -papp_password app_db
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  Success" -ForegroundColor Green
    } else {
        Write-Host "  Failed" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Running seeds..." -ForegroundColor Cyan

$seeds = Get-ChildItem -Path "database/seeds" -Filter "*.sql" | Sort-Object Name

foreach ($seed in $seeds) {
    Write-Host "Applying: $($seed.Name)" -ForegroundColor Yellow
    Get-Content $seed.FullName | docker-compose -f docker-compose.dev.yml exec -T db mysql -uapp_user -papp_password app_db
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  Success" -ForegroundColor Green
    } else {
        Write-Host "  Failed" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "Migrations completed!" -ForegroundColor Green
