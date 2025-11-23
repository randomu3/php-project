# Update email templates

Write-Host "Updating email templates..." -ForegroundColor Cyan

# Get script directory and project root
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Split-Path -Parent $scriptDir

# Change to project root
Push-Location $projectRoot

try {
    # Apply seed file with UTF-8 encoding
    Get-Content -Encoding UTF8 database/seeds/default_templates.sql | docker-compose -f docker-compose.dev.yml exec -T db mysql -uroot -proot_password --default-character-set=utf8mb4 app_db

    if ($LASTEXITCODE -eq 0) {
        Write-Host "Templates updated successfully!" -ForegroundColor Green
        Write-Host ""
        Write-Host "Available templates:" -ForegroundColor Yellow
        Write-Host "  - newsletter: News broadcast"
        Write-Host "  - announcement: Important announcement"
        Write-Host "  - promo: Special offer"
    } else {
        Write-Host "Error updating templates" -ForegroundColor Red
        exit 1
    }
} finally {
    # Return to original directory
    Pop-Location
}
