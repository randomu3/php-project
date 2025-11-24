---
inclusion: always
---

# PHPDoc Standards

## Always Add PHPDoc

Every class and method MUST have PHPDoc comments following PSR-5 standard.

## Format

```php
<?php

namespace AuraUI\Helpers;

/**
 * Notification manager for user notifications
 *
 * Handles creation, retrieval, and management of user notifications
 * with support for different notification types and read status tracking.
 *
 * @package AuraUI\Helpers
 * @author AuraUI Team
 * @version 1.0.0
 */
class NotificationManager
{
    /**
     * Create new notification
     *
     * @param int $userId User ID
     * @param string $type Notification type (success, warning, error, info)
     * @param string $title Notification title
     * @param string $message Notification message content
     * @param string|null $link Optional link URL (default: null)
     * @param string|null $icon Optional icon name (default: null)
     *
     * @return bool True on success, false on failure
     * @throws PDOException When database operation fails
     */
    public function create(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $link = null,
        ?string $icon = null
    ): bool {
        // implementation
    }
}
```

## Required Tags

### For Classes:
- `@package` - Namespace/package name
- `@author` - Author name (optional)
- `@version` - Version number (optional)

### For Methods:
- `@param` - For EVERY parameter (type + name + description)
- `@return` - For ALL methods (use `void` if no return)
- `@throws` - For exceptions that can be thrown

## Rules

1. **Language**: ALL PHPDoc MUST be in English
2. **Completeness**: Every parameter and return MUST be documented
3. **Types**: Always specify types (int, string, bool, array, mixed, etc.)
4. **Descriptions**: Add meaningful descriptions, not just repeating the name
5. **Formatting**: Follow PSR-12 with proper indentation and blank lines
6. **Nullability**: Use `?type` or `type|null` for nullable parameters

## Type Hints

Use PHP 7.4+ type hints in method signatures:
- `int`, `string`, `bool`, `array`, `float`
- `?type` for nullable
- `ClassName` for objects
- `mixed` for any type (PHP 8.0+)

## Examples

### Constructor:
```php
/**
 * Constructor
 *
 * Initializes database connection and sets up notification system.
 */
public function __construct()
```

### Method with parameters:
```php
/**
 * Get user notifications
 *
 * Retrieves notifications for specified user with optional filtering
 * by read status and result limiting.
 *
 * @param int $userId User ID
 * @param int $limit Maximum number of results (default: 10)
 * @param bool $unreadOnly Return only unread notifications (default: false)
 *
 * @return array Array of notification objects
 */
public function getUserNotifications(
    int $userId,
    int $limit = 10,
    bool $unreadOnly = false
): array
```

### Method with nullable return:
```php
/**
 * Find notification by ID
 *
 * @param int $id Notification ID
 *
 * @return array|null Notification data or null if not found
 */
public function findById(int $id): ?array
```

## Auto-generation

Kiro Hook automatically adds PHPDoc when you save PHP files.
It will:
- Detect parameter types from code
- Detect return types from return statements
- Generate descriptions from method names
- Add proper formatting

You can then improve the auto-generated comments with more specific descriptions.
