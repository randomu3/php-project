# Скрипт для подключения к БД и просмотра данных

Write-Host "🗄️  Подключение к базе данных..." -ForegroundColor Cyan
Write-Host ""

# Показываем всех пользователей
Write-Host "👥 Пользователи в системе:" -ForegroundColor Green
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e 'SELECT id, username, email, created_at, last_login FROM users;'

Write-Host ""
Write-Host "🔑 Токены восстановления пароля:" -ForegroundColor Green
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e 'SELECT id, user_id, token, created_at, expires_at, used FROM password_resets ORDER BY created_at DESC LIMIT 5;'

Write-Host ""
Write-Host "📊 Статистика:" -ForegroundColor Yellow
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e 'SELECT COUNT(*) as total_users FROM users;'
