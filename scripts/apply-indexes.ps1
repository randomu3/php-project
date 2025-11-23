# Apply database indexes for performance

Write-Host "Applying database indexes..." -ForegroundColor Cyan

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Split-Path -Parent $scriptDir
Push-Location $projectRoot

try {
    Get-Content -Encoding UTF8 -Raw database/migrations/011_add_indexes.sql | docker-compose -f docker-compose.dev.yml exec -T db mysql -uroot -proot_password --default-character-set=utf8mb4 app_db
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "SUCCESS! Indexes applied!" -ForegroundColor Green
        Write-Host "Database queries will be much faster now!" -ForegroundColor Yellow
    } else {
        Write-Host "ERROR applying indexes" -ForegroundColor Red
    }
} finally {
    Pop-Location
}
