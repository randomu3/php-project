# Скрипт первоначальной настройки проекта

Write-Host "=== Project Setup ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. Starting Docker containers..." -ForegroundColor Yellow
docker-compose -f docker-compose.dev.yml up -d

Write-Host ""
Write-Host "2. Waiting for database..." -ForegroundColor Yellow
Start-Sleep -Seconds 5

Write-Host ""
Write-Host "3. Running migrations..." -ForegroundColor Yellow
& "$PSScriptRoot\migrate.ps1"

Write-Host ""
Write-Host "4. Creating admin user..." -ForegroundColor Yellow
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "UPDATE users SET is_admin = TRUE WHERE email = 'demiz99@mail.ru';"

Write-Host ""
Write-Host "=== Setup Complete! ===" -ForegroundColor Green
Write-Host ""
Write-Host "Open in browser:" -ForegroundColor Cyan
Write-Host "  http://localhost:8080" -ForegroundColor White
Write-Host ""
Write-Host "Admin credentials:" -ForegroundColor Cyan
Write-Host "  Username: demiz99" -ForegroundColor White
Write-Host "  Password: SecurePass123!" -ForegroundColor White
