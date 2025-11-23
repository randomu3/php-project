# Быстрый вход в админку

Write-Host "🔐 Вход в админ-панель..." -ForegroundColor Cyan
Write-Host ""

# Открываем страницу логина
Start-Process "http://localhost:8080/login"

Write-Host "Войдите с учетными данными:" -ForegroundColor Yellow
Write-Host "  Username: demiz99" -ForegroundColor White
Write-Host "  Password: SecurePass123!" -ForegroundColor White
Write-Host ""
Write-Host "После входа откроется админ-панель..." -ForegroundColor Gray

Start-Sleep -Seconds 3
Start-Process "http://localhost:8080/admin"

Write-Host ""
Write-Host "✅ Страницы открыты в браузере" -ForegroundColor Green
Write-Host ""
Write-Host "Если видите 'Доступ запрещен', значит:" -ForegroundColor Yellow
Write-Host "  1. Вы не вошли в систему" -ForegroundColor Gray
Write-Host "  2. Или у пользователя нет прав администратора" -ForegroundColor Gray
