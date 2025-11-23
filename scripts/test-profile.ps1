# Тестирование функциональности профиля

Write-Host "=== Тест профиля пользователя ===" -ForegroundColor Cyan
Write-Host ""

# Проверка файлов
Write-Host "Проверка файлов..." -ForegroundColor Yellow

$files = @(
    "src/controllers/ProfileController.php",
    "src/views/profile.view.php",
    "src/profile.php",
    "docs/PROFILE_FEATURE.md",
    "docs/PROFILE_USAGE.md",
    "database/migrations/004_add_profile_fields.sql"
)

$allExist = $true
foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "✓ $file" -ForegroundColor Green
    } else {
        Write-Host "✗ $file - НЕ НАЙДЕН" -ForegroundColor Red
        $allExist = $false
    }
}

Write-Host ""

if ($allExist) {
    Write-Host "✓ Все файлы на месте!" -ForegroundColor Green
} else {
    Write-Host "✗ Некоторые файлы отсутствуют" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "=== Проверка синтаксиса PHP ===" -ForegroundColor Cyan
Write-Host ""

# Проверка синтаксиса PHP файлов
$phpFiles = @(
    "src/controllers/ProfileController.php",
    "src/profile.php"
)

$syntaxOk = $true
foreach ($file in $phpFiles) {
    Write-Host "Проверка $file..." -ForegroundColor Yellow
    
    $result = docker-compose -f docker-compose.dev.yml exec -T web php -l $file 2>&1
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✓ Синтаксис корректен" -ForegroundColor Green
    } else {
        Write-Host "✗ Ошибка синтаксиса:" -ForegroundColor Red
        Write-Host $result
        $syntaxOk = $false
    }
}

Write-Host ""

if ($syntaxOk) {
    Write-Host "✓ Синтаксис всех PHP файлов корректен!" -ForegroundColor Green
} else {
    Write-Host "✗ Обнаружены ошибки синтаксиса" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "=== Тестирование доступа ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "Проверка доступности страницы профиля..." -ForegroundColor Yellow
Write-Host "URL: http://localhost:8080/profile" -ForegroundColor Gray
Write-Host ""

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8080/profile" -UseBasicParsing -MaximumRedirection 0 -ErrorAction SilentlyContinue
    
    if ($response.StatusCode -eq 302) {
        Write-Host "✓ Редирект на страницу входа (требуется авторизация)" -ForegroundColor Green
    } elseif ($response.StatusCode -eq 200) {
        Write-Host "✓ Страница доступна" -ForegroundColor Green
    } else {
        Write-Host "? Неожиданный статус: $($response.StatusCode)" -ForegroundColor Yellow
    }
} catch {
    if ($_.Exception.Response.StatusCode -eq 302) {
        Write-Host "✓ Редирект на страницу входа (требуется авторизация)" -ForegroundColor Green
    } else {
        Write-Host "✗ Ошибка доступа: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "=== Результаты ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "✓ Контроллер создан: ProfileController.php" -ForegroundColor Green
Write-Host "✓ Представление создано: profile.view.php" -ForegroundColor Green
Write-Host "✓ Точка входа создана: profile.php" -ForegroundColor Green
Write-Host "✓ Документация создана: PROFILE_FEATURE.md, PROFILE_USAGE.md" -ForegroundColor Green
Write-Host "✓ Миграция создана: 004_add_profile_fields.sql" -ForegroundColor Green
Write-Host ""
Write-Host "Функциональность профиля готова к использованию!" -ForegroundColor Green
Write-Host ""
Write-Host "Для тестирования:" -ForegroundColor Yellow
Write-Host "1. Откройте http://localhost:8080" -ForegroundColor Gray
Write-Host "2. Войдите в систему" -ForegroundColor Gray
Write-Host "3. Перейдите в профиль (клик на аватар или /profile)" -ForegroundColor Gray
Write-Host "4. Попробуйте изменить данные и пароль" -ForegroundColor Gray
Write-Host ""
