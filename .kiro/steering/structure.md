---
inclusion: always
---

# Architecture & Code Conventions

## MVC Structure
- `src/*.php` - Entry points that route to controllers
- `src/controllers/` - Class-based controllers with `index()` method for GET
- `src/views/*.view.php` - HTML templates (minimal PHP, output only)
- `src/views/partials/` - Reusable components
- `src/assets/js/app.js` - jQuery client logic
- `src/config.php` - Configuration and utility functions
- `database/migrations/` - Sequential SQL files (`001_description.sql`)
- `database/seeds/` - Initial data

## PHP Conventions

### Controllers
- Class-based with public `index()` for GET requests
- Private methods for POST/PUT/DELETE actions
- Wrap all DB operations in try-catch blocks

### Database Access
- Use `getDB()` singleton for connections
- PDO with `PDO::FETCH_ASSOC` mode
- Always use prepared statements (never concatenate SQL)

### Security Rules (Non-Negotiable)
- CSRF: `generateCSRFToken()` in forms, `verifyCSRFToken()` on submission
- Input: `sanitizeInput()` for all user input
- Output: `htmlspecialchars()` for all displayed data
- Passwords: Argon2ID via `password_hash()` and `password_verify()`
- Sessions: `isLoggedIn()`, `requireLogin()`, `requireAdmin()`
- Brute-force: 5 failed attempts triggers 15-minute lockout

### Views
- Minimal PHP (output and simple conditionals only)
- Always escape output: `<?= htmlspecialchars($var) ?>`
- Include partials: `require __DIR__ . '/partials/component.php'`
- Receive data via variables passed from controller

## Frontend Conventions

### JavaScript
- All logic in `src/assets/js/app.js`
- jQuery for DOM manipulation and AJAX
- Initialize Lucide icons with `lucide.createIcons()`

### CSS
- Prefer Tailwind utility classes
- Custom styles in `src/assets/css/style.css`
- Design: dark theme with glassmorphism

## Naming Conventions
- Controllers: `PascalCase` + `Controller` suffix (e.g., `LoginController.php`)
- Views: `lowercase` + `.view.php` suffix (e.g., `login.view.php`)
- Partials: `lowercase.php` (e.g., `users_table.php`)
- Functions: `camelCase` (e.g., `isLoggedIn()`)
- DB tables: `snake_case` (e.g., `password_resets`)
- CSS: Tailwind utilities or `kebab-case`

## Adding Features

### New Page
1. Create controller in `src/controllers/NewController.php`
2. Create view in `src/views/new.view.php`
3. Create entry point in `src/new.php`
4. Update `.htaccess` if clean URLs needed

### New Database Table
1. Create migration in `database/migrations/XXX_create_table.sql`
2. Run `.\scripts\migrate.ps1`

### New Email Template
Add to `database/seeds/default_templates.sql` or create via admin panel
