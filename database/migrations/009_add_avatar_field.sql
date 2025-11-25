-- Миграция: Добавление поля для аватара
-- Создание: 2025-01-24

SET @dbname = DATABASE();
SET @tablename = 'users';
SET @columnname = 'avatar';

SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER email'
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;
