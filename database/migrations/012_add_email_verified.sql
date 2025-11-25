-- Миграция: Добавление поля email_verified для подтверждения регистрации
-- Создание: 2025-11-25

-- Добавляем поля для верификации email (используем процедуру для проверки существования)
SET @dbname = DATABASE();
SET @tablename = 'users';

-- Добавляем email_verified если не существует
SET @columnname = 'email_verified';
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN email_verified TINYINT(1) DEFAULT 0 AFTER email'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Добавляем email_verification_token если не существует
SET @columnname = 'email_verification_token';
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN email_verification_token VARCHAR(64) NULL AFTER email_verified'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Добавляем email_verification_expires если не существует
SET @columnname = 'email_verification_expires';
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN email_verification_expires DATETIME NULL AFTER email_verification_token'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Помечаем существующих пользователей как подтверждённых (для обратной совместимости)
UPDATE users SET email_verified = 1 WHERE email_verified = 0 OR email_verified IS NULL;
