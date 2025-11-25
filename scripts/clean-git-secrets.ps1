# Скрипт для очистки секретов из истории Git
# ВНИМАНИЕ: Это перепишет всю историю репозитория!

Write-Host "=== ОЧИСТКА СЕКРЕТОВ ИЗ GIT ИСТОРИИ ===" -ForegroundColor Red
Write-Host ""
Write-Host "ВАЖНО:" -ForegroundColor Yellow
Write-Host "1. Этот скрипт перепишет ВСЮ историю репозитория"
Write-Host "2. Потребуется force push (git push --force)"
Write-Host "3. Все участники должны будут пере-клонировать репозиторий"
Write-Host "4. Скомпрометированные ключи нужно отозвать и создать новые!"
Write-Host ""

$confirm = Read-Host "Вы уверены? (yes/no)"
if ($confirm -ne "yes") {
    Write-Host "Отменено." -ForegroundColor Yellow
    exit
}

# Секреты для удаления (замените на свои значения)
$secrets = @(
    "re_brMPxT9m_BEgFoPQucTe22E1QcAw5svTH",  # Resend API Key
    "root_password",                          # MySQL root password
    "app_password"                            # MySQL user password
)

Write-Host ""
Write-Host "Создание резервной копии..." -ForegroundColor Cyan
git clone --mirror . ../php-project-backup-$(Get-Date -Format 'yyyyMMdd-HHmmss')

Write-Host ""
Write-Host "Очистка секретов из истории..." -ForegroundColor Cyan

foreach ($secret in $secrets) {
    Write-Host "Удаление: $($secret.Substring(0, [Math]::Min(10, $secret.Length)))..." -ForegroundColor Yellow
    
    # Используем git filter-branch для замены секретов
    git filter-branch --force --tree-filter "find . -type f -name '*.yml' -o -name '*.yaml' -o -name '*.php' -o -name '*.env' | xargs -r sed -i 's/$secret/[REDACTED]/g'" --prune-empty HEAD 2>$null
}

Write-Host ""
Write-Host "Очистка reflog и gc..." -ForegroundColor Cyan
git reflog expire --expire=now --all
git gc --prune=now --aggressive

Write-Host ""
Write-Host "=== ГОТОВО ===" -ForegroundColor Green
Write-Host ""
Write-Host "Следующие шаги:" -ForegroundColor Yellow
Write-Host "1. Проверьте историю: git log --oneline"
Write-Host "2. Push с force: git push origin --force --all"
Write-Host "3. Отзовите старый Resend API ключ на resend.com"
Write-Host "4. Создайте новый API ключ и обновите .env"
Write-Host "5. Сообщите всем участникам пере-клонировать репозиторий"
