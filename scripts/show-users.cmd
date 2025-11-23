@echo off
chcp 65001 >nul
echo.
echo === Пользователи в системе ===
echo.
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "SELECT id, username, email, created_at FROM users;"
echo.
echo === Токены восстановления пароля ===
echo.
docker-compose -f docker-compose.dev.yml exec db mysql -uapp_user -papp_password app_db -e "SELECT id, user_id, LEFT(token, 20) as token_start, created_at, expires_at, used FROM password_resets ORDER BY created_at DESC LIMIT 5;"
echo.
pause
