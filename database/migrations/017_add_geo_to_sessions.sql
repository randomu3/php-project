-- Add geo fields to user_sessions
-- Migration: 017_add_geo_to_sessions.sql

ALTER TABLE user_sessions 
ADD COLUMN IF NOT EXISTS country VARCHAR(100) NULL AFTER device_info,
ADD COLUMN IF NOT EXISTS city VARCHAR(100) NULL AFTER country;

-- Add index for geo queries
ALTER TABLE user_sessions ADD INDEX IF NOT EXISTS idx_country (country);
