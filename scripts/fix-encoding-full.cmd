@echo off
chcp 65001 >nul
echo.
echo 🔥 ПОЛНОЕ ИСПРАВЛЕНИЕ КОДИРОВКИ
echo.
echo Это пересоздаст контейнеры с правильной кодировкой UTF-8
echo.
echo ⚠️  ВНИМАНИЕ: Все данные в БД будут удалены!
echo.
set /p confirm="Продолжить? (y/n): "
if /i not "%confirm%"=="y" exit /b

echo.
echo 1️⃣ Остановка контейнеров...
docker-compose -f docker-compose.dev.yml down

echo.
echo 2️⃣ Удаление volume БД...
docker volume rm phpproject_db_data 2>nul

echo.
echo 3️⃣ Пересоздание контейнеров с правильной кодировкой...
docker-compose -f docker-compose.dev.yml up --build -d

echo.
echo 4️⃣ Ожидание запуска БД (10 секунд)...
timeout /t 10 /nobreak >nul

echo.
echo 5️⃣ Проверка кодировки БД...
docker-compose -f docker-compose.dev.yml exec -T db mysql -u app_user -papp_password app_db -e "SHOW VARIABLES LIKE 'character_set%%';" 2>nul

echo.
echo 6️⃣ Проверка шаблонов...
docker-compose -f docker-compose.dev.yml exec -T db mysql -u app_user -papp_password app_db --default-character-set=utf8mb4 -e "SELECT id, name, subject FROM email_templates;" 2>nul

echo.
echo ✅ ГОТОВО!
echo.
echo 📋 Проверьте:
echo    http://localhost:8080/test-email-templates
echo    http://localhost:8080/admin
echo.
echo 🔑 Войдите заново (сессии сброшены):
echo    Email: admin@example.com
echo    Password: admin123
echo.
pause
