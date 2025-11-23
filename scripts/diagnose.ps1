# Диагностика проблемы с админкой

Write-Host "=== Диагностика админки ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. Проверка пользователя в БД:" -ForegroundColor Yellow
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "SELECT id, username, email, is_admin FROM users WHERE username = 'demiz99';"

Write-Host ""
Write-Host "2. Проверка структуры таблицы:" -ForegroundColor Yellow
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "DESCRIBE users;"

Write-Host ""
Write-Host "3. Откройте в браузере:" -ForegroundColor Yellow
Write-Host "   http://localhost:8080/check_admin" -ForegroundColor White
Write-Host ""
Write-Host "4. Если is_admin = 1, но всё равно не пускает:" -ForegroundColor Yellow
Write-Host "   - Выйдите из системы (http://localhost:8080/logout)" -ForegroundColor Gray
Write-Host "   - Войдите заново (http://localhost:8080/login)" -ForegroundColor Gray
Write-Host "   - Попробуйте админку (http://localhost:8080/admin)" -ForegroundColor Gray
