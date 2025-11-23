---
inclusion: always
---

# Product Overview

PHP authentication system with user management, email notifications, and admin panel.

## Core Features
- User registration/login with Argon2ID password hashing
- Password recovery via email
- Admin panel for user and email template management
- Database-stored, customizable email templates
- Clean URLs (no .php extensions via `.htaccess`)

## Security Implementation
- CSRF protection on all forms
- Brute-force prevention: 5 failed attempts → 15-minute lockout
- XSS protection via output escaping
- SQL injection prevention via prepared statements
- Secure session handling

## Email System
- Powered by Resend API (`resend/resend-php`)
- Templates: welcome email, password reset
- Test mode limitation: sends only to demiz99@mail.ru
- Production requires Resend domain verification for unrestricted delivery

## User Roles
- Regular users: profile management, password changes
- Admins: user management, email template editing, system configuration
