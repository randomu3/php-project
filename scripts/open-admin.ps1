# Скрипт для открытия админ-панели

Write-Host "🔐 Открываем админ-панель..." -ForegroundColor Cyan
Write-Host ""
Write-Host "Если вы не авторизованы, сначала войдите:" -ForegroundColor Yellow
Write-Host "   Username: demiz99" -ForegroundColor Gray
Write-Host "   Password: SecurePass123!" -ForegroundColor Gray
Write-Host ""

Start-Process "http://localhost:8080/login"
Start-Sleep -Seconds 2
Start-Process "http://localhost:8080/admin"

Write-Host "✅ Страницы открыты в браузере" -ForegroundColor Green
