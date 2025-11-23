# Скрипт для создания бэкапа БД

$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$backupFile = "backup_$timestamp.sql"

Write-Host "Creating database backup..." -ForegroundColor Cyan
Write-Host "File: $backupFile" -ForegroundColor Gray
Write-Host ""

docker-compose -f docker-compose.dev.yml exec -T db mysqldump -uapp_user -papp_password app_db > "database/backups/$backupFile"

if ($LASTEXITCODE -eq 0) {
    Write-Host "Backup created successfully!" -ForegroundColor Green
    Write-Host "Location: database/backups/$backupFile" -ForegroundColor White
} else {
    Write-Host "Backup failed!" -ForegroundColor Red
}
