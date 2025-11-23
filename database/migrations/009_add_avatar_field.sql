-- Миграция: Добавление поля для аватара

ALTER TABLE users ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) NULL AFTER email;

-- Комментарий
ALTER TABLE users MODIFY COLUMN avatar VARCHAR(255) NULL COMMENT 'Путь к файлу аватара пользователя';
