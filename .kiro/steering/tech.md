---
inclusion: always
---

# Technology Stack

## Core Stack
- PHP 8.2, MySQL 8.0, Apache 2.4, PDO, Composer
- jQuery 3.7.1, Tailwind CSS, Lucide Icons
- Resend API (`resend/resend-php` ^0.13.0)
- Docker with hot-reload dev environment
- Windows platform (PowerShell/cmd)

## Docker Commands
Development uses `docker-compose.dev.yml`, production uses `docker-compose.yml`:
```bash
# Development
docker-compose -f docker-compose.dev.yml up --build -d
docker-compose -f docker-compose.dev.yml logs -f web
docker-compose -f docker-compose.dev.yml restart web
docker-compose -f docker-compose.dev.yml down

# Production (no -f flag)
docker-compose up -d
```

## Database Scripts
Use PowerShell scripts in `scripts/` directory:
- `.\scripts\migrate.ps1` - Run migrations
- `.\scripts\db-connect.ps1` - Connect to MySQL CLI
- `.\scripts\show-users.cmd` - List all users
- `.\scripts\backup.ps1` - Backup database

## Environment Variables
Configure in `docker-compose.yml` or `docker-compose.dev.yml`:
- `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `RESEND_API_KEY`, `FROM_EMAIL`, `FROM_NAME`

## Local Access
- App: http://localhost:8080
- Admin: http://localhost:8080/admin
- MySQL: localhost:3306
