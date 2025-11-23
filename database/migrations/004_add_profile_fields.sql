-- Миграция: Добавление дополнительных полей профиля (для будущего расширения)

-- Добавляем поля для аватара и дополнительной информации
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS avatar_url VARCHAR(255) NULL AFTER email,
ADD COLUMN IF NOT EXISTS bio TEXT NULL AFTER avatar_url,
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) NULL AFTER bio,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP AFTER created_at;

-- Индекс для быстрого поиска по телефону
CREATE INDEX IF NOT EXISTS idx_phone ON users(phone);
