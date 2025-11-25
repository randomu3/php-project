-- AuraUI Database Backup
-- Generated: 2025-11-25 15:29:31
-- Database: app_db

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_entity` (`entity_type`,`entity_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('1', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:32:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('2', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:33:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('3', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:33:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('4', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:33:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('5', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:33:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('6', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:34:05');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('7', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:34:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('8', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:34:09');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('9', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:34:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('10', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:36:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('11', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:37:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('12', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:41:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('13', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 11:41:34');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('14', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:01:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('15', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:07:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('16', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:14:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('17', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:14:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('18', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:15:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('19', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:15:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('20', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:34:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('21', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:34:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('22', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:35:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('23', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:36:12');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('24', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 12:36:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('25', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 13:13:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('26', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 14:33:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('27', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 14:44:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('28', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:03:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('29', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:03:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('30', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:14:07');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('31', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:18');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('32', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:22');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('33', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:23');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('34', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:29');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('35', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('36', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('37', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('38', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('39', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('40', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:44');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('41', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('42', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('43', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('44', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:51');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('45', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('46', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('47', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('48', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('49', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('50', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('51', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('52', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:27:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('53', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:28:39');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('54', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:28:40');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('55', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-23 15:28:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('56', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 10:19:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('57', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 10:19:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('58', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 10:20:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('59', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 10:20:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('60', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 10:21:02');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('61', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 10:54:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('62', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:16:50');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('63', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:16:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('64', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:52:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('65', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:53:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('66', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:53:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('67', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:53:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('68', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:56:32');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('69', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:56:42');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('70', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:58:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('71', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 11:58:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('72', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:00:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('73', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:00:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('74', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:00:41');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('75', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:00:46');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('76', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:00:49');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('77', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:01:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('78', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:01:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('79', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:01:28');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('80', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:01:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('81', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:06:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('82', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:11:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('83', NULL, 'user.logout', 'Пользователь вышел из системы', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:11:38');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('84', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:12:53');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('85', NULL, 'user.logout', 'Пользователь вышел из системы', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:12:54');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('86', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:16:35');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('87', NULL, 'user.logout', 'Пользователь вышел из системы', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:16:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('88', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:28:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('89', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:28:24');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('90', NULL, 'user.logout', 'Пользователь вышел из системы', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:28:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('91', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:36:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('92', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:36:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('93', '6', 'user.update_profile', 'Удален аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:36:58');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('94', '6', 'user.update_profile', 'Загружен новый аватар', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:37:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('95', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99@mail.ru, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:37:10');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('96', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:37:14');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('97', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99232@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:37:17');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('98', '6', 'user.update_profile', 'Обновлен профиль: username=demiz99, email=demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 12:37:20');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('99', '6', 'user.update_profile', 'Запрошено изменение email на demizec@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:03:37');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('100', '6', 'user.update_profile', 'Обновлен username: demiz99', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:03:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('101', '6', 'user.update_profile', 'Запрошено изменение email на demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:05:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('102', '6', 'user.update_profile', 'Email изменен с oldmail@example.com на demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:05:59');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('103', '6', 'user.update_profile', 'Запрошено изменение email на demiz99@example.com', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:07:55');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('104', '6', 'user.update_profile', 'Запрошено изменение email на demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:08:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('105', '6', 'user.update_profile', 'Email изменен с oldmail@example.com на demiz99@mail.ru', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 13:08:13');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('106', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-24 14:44:36');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `entity_type`, `entity_id`, `ip_address`, `user_agent`, `created_at`) VALUES ('107', '6', 'user.login', 'Пользователь demiz99 вошел в систему', 'user', '6', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 15:29:06');

DROP TABLE IF EXISTS `admin_notification_settings`;
CREATE TABLE `admin_notification_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `notify_new_registration` tinyint(1) DEFAULT '1',
  `notify_suspicious_activity` tinyint(1) DEFAULT '1',
  `notify_failed_logins` tinyint(1) DEFAULT '1',
  `email_reports` tinyint(1) DEFAULT '0',
  `email_report_frequency` enum('daily','weekly','monthly') COLLATE utf8mb4_unicode_ci DEFAULT 'daily',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  CONSTRAINT `admin_notification_settings_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `admin_notifications`;
CREATE TABLE `admin_notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('registration','security','system','report') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `blocked_ips`;
CREATE TABLE `blocked_ips` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blocked_by` int DEFAULT NULL,
  `blocked_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime DEFAULT NULL,
  `is_permanent` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ip` (`ip_address`),
  KEY `idx_expires` (`expires_at`),
  KEY `blocked_by` (`blocked_by`),
  CONSTRAINT `blocked_ips_ibfk_1` FOREIGN KEY (`blocked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `email_templates`;
CREATE TABLE `email_templates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `email_templates` (`id`, `name`, `subject`, `body`, `description`, `created_at`, `updated_at`) VALUES ('1', 'newsletter', 'Новости', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;\">\r\n    <div style=\"background: white; padding: 30px; border-radius: 8px;\">\r\n        <div style=\"text-align: center; margin-bottom: 30px;\">\r\n            <div style=\"display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 25px;\">\r\n                <h2 style=\"color: white; margin: 0; font-size: 20px;\">📢 {{subject}}</h2>\r\n            </div>\r\n        </div>\r\n        <p style=\"color: #333; font-size: 16px;\">Здравствуйте, {{username}}!</p>\r\n        <div style=\"margin: 25px 0; padding: 25px; background: #f8f9fa; border-left: 5px solid #667eea; border-radius: 8px;\">\r\n            <div style=\"color: #333; font-size: 16px;\">{{message}}</div>\r\n        </div>\r\n    </div>\r\n</div>', 'Рассылка новостей всем пользователям', '2025-11-23 14:23:39', '2025-11-23 14:23:39');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `body`, `description`, `created_at`, `updated_at`) VALUES ('2', 'announcement', 'Важное объявление', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px;\">\r\n    <div style=\"background: white; padding: 30px; border-radius: 8px;\">\r\n        <h2 style=\"color: #f5576c;\">⚠️ Важное объявление</h2>\r\n        <p style=\"color: #333;\">Уважаемый {{username}},</p>\r\n        <div style=\"margin: 25px 0; padding: 25px; background: #fff3cd; border-left: 5px solid #ffc107; border-radius: 8px;\">\r\n            <div style=\"color: #856404;\">{{message}}</div>\r\n        </div>\r\n    </div>\r\n</div>', 'Важные объявления и уведомления', '2025-11-23 14:23:39', '2025-11-23 14:23:39');
INSERT INTO `email_templates` (`id`, `name`, `subject`, `body`, `description`, `created_at`, `updated_at`) VALUES ('3', 'promo', 'Специальное предложение', '<div style=\"font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px;\">\r\n    <div style=\"background: white; padding: 30px; border-radius: 8px;\">\r\n        <h2 style=\"color: #4facfe;\">🎁 Специальное предложение</h2>\r\n        <p style=\"color: #333;\">Привет, {{username}}!</p>\r\n        <div style=\"margin: 25px 0; padding: 25px; background: #e0f7ff; border-radius: 12px; text-align: center;\">\r\n            <div style=\"color: #0277bd; font-size: 18px;\">{{message}}</div>\r\n        </div>\r\n    </div>\r\n</div>', 'Промо-акции и специальные предложения', '2025-11-23 14:23:39', '2025-11-23 14:23:39');

DROP TABLE IF EXISTS `email_verifications`;
CREATE TABLE `email_verifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `new_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `email_verifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `attempted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `success` tinyint(1) DEFAULT '0',
  `failure_reason` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip_address`),
  KEY `idx_username` (`username`),
  KEY `idx_attempted_at` (`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `user_agent`, `attempted_at`, `success`, `failure_reason`) VALUES ('1', '172.25.0.1', 'demiz99@mail.ru', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 15:29:06', '1', NULL);

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('1', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:32:56', '2025-11-23 11:33:07');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('2', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:33:04', '2025-11-23 11:34:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('3', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:33:07', '2025-11-23 11:33:09');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('4', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:33:09', '2025-11-23 11:34:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('5', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:33:22', '2025-11-23 11:34:04');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('6', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:34:05', '2025-11-23 11:34:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('7', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:34:06', '2025-11-23 11:34:09');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('8', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:34:09', '2025-11-23 11:34:11');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('9', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:34:11', '2025-11-23 11:37:16');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('10', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:36:41', '2025-11-23 11:37:16');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('11', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:37:30', '2025-11-23 11:37:35');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('12', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:41:24', '2025-11-23 11:41:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('13', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-23 11:41:34', '2025-11-23 11:44:10');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('14', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:42', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('15', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:43', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('16', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:44', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('17', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:50', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('18', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:50', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('19', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:50', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('20', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:51', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('21', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:52', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('22', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:52', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('23', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:53', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('24', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:54', '2025-11-23 15:28:11');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('25', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:54', '2025-11-23 15:28:23');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('26', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:54', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('27', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:55', '2025-11-23 15:28:26');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('28', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:27:55', '2025-11-23 15:28:08');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('29', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:28:39', '2025-11-23 15:28:46');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('30', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:28:40', '2025-11-23 15:28:46');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('31', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-23 15:28:41', '2025-11-23 15:28:46');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('32', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-24 11:52:55', '2025-11-24 11:53:03');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('33', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 11:56:32', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('34', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 11:56:42', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('35', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 11:58:13', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('36', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 11:58:17', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('37', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:00:35', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('38', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:00:38', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('39', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:00:41', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('40', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:00:46', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('41', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:00:49', '2025-11-24 12:01:06');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('42', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:01:16', '2025-11-24 12:01:36');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('43', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:01:26', '2025-11-24 12:01:36');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('44', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:01:28', '2025-11-24 12:01:36');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('45', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:36:55', '2025-11-24 13:03:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('46', '6', 'success', 'Аватар обновлен', 'Ваш аватар успешно загружен', '/profile', 'check-circle', '1', '2025-11-24 12:37:01', '2025-11-24 13:03:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('47', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-24 12:37:10', '2025-11-24 13:03:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('48', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-24 12:37:14', '2025-11-24 13:03:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('49', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-24 12:37:18', '2025-11-24 13:03:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('50', '6', 'success', 'Профиль обновлен', 'Ваши данные успешно обновлены', '/profile', 'check-circle', '1', '2025-11-24 12:37:20', '2025-11-24 13:03:27');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('51', '6', 'info', 'Подтвердите email', 'Письмо с подтверждением отправлено на новый адрес', '/profile', 'info', '1', '2025-11-24 13:03:37', '2025-11-24 13:03:56');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('52', '6', 'success', 'Профиль обновлен', 'Ваше имя пользователя успешно обновлено', '/profile', 'check-circle', '1', '2025-11-24 13:03:47', '2025-11-24 13:03:56');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('53', '6', 'info', 'Подтвердите email', 'Письмо с подтверждением отправлено на новый адрес', '/profile', 'info', '1', '2025-11-24 13:05:45', '2025-11-24 14:44:41');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('54', '6', 'success', 'Email подтвержден', 'Ваш email адрес успешно изменен', '/profile', 'check-circle', '1', '2025-11-24 13:05:59', '2025-11-24 14:44:41');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('55', '6', 'info', 'Подтвердите email', 'Письмо с подтверждением отправлено на новый адрес', '/profile', 'info', '1', '2025-11-24 13:07:55', '2025-11-24 14:44:41');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('56', '6', 'info', 'Подтвердите email', 'Письмо с подтверждением отправлено на новый адрес', '/profile', 'info', '1', '2025-11-24 13:08:01', '2025-11-24 14:44:41');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `icon`, `is_read`, `created_at`, `read_at`) VALUES ('57', '6', 'success', 'Email подтвержден', 'Ваш email адрес успешно изменен', '/profile', 'check-circle', '1', '2025-11-24 13:08:13', '2025-11-24 14:44:41');

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_token` (`token`),
  KEY `idx_password_resets_token` (`token`),
  KEY `idx_password_resets_user_id` (`user_id`),
  KEY `idx_password_resets_expires_at` (`expires_at`),
  KEY `idx_password_resets_used` (`used`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('1', '6', '10b230cf06ce7c91de86883d7e5280773a3a305fb0253a1a455fc34a028bf07e', '2025-11-23 08:55:56', '2025-11-23 09:55:56', '1');
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('2', '6', 'b055f2a6c33ff7dfe2ee7cb29014b80cf1a118825d33d1a1f7e7f193e4050bca', '2025-11-23 08:57:38', '2025-11-23 09:57:38', '0');
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('3', '6', '2f7155d8f67b51d6c8cc2e3b86815a1c4a7607d31dd0f0cca64d575c8f6fc429', '2025-11-24 12:12:56', '2025-11-24 13:12:56', '0');
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('4', '6', 'c35bf44205b8bb4573de9ba1956c9cd484d340191fb828436d82aadee7759688', '2025-11-24 12:14:00', '2025-11-24 13:14:00', '0');
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('5', '6', '01d5be791a1542e8eaa751f252c7d09170e48eebe3535fc7fe87e4196f13960b', '2025-11-24 12:14:35', '2025-11-24 13:14:35', '0');
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('6', '6', 'cc566ec8d25881891581fd37fa5fc3ef103030d5596fdff4d3ccb1d91af84fdb', '2025-11-24 12:16:03', '2025-11-24 13:16:03', '1');
INSERT INTO `password_resets` (`id`, `user_id`, `token`, `created_at`, `expires_at`, `used`) VALUES ('7', '6', '3580298ce78dd17dfca45f95f66295b588d9db8444813b8bd22570ee6494ec65', '2025-11-24 12:21:05', '2025-11-24 13:21:05', '1');

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_name` (`name`),
  KEY `idx_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('1', 'users.view', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ ÑÐ¿Ð¸ÑÐºÐ° Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'users', '2025-11-23 11:21:48');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('2', 'users.create', 'Ð¡Ð¾Ð·Ð´Ð°Ð½Ð¸Ðµ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'Ð¡Ð¾Ð·Ð´Ð°Ð½Ð¸Ðµ Ð½Ð¾Ð²Ñ‹Ñ… Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'users', '2025-11-23 11:21:48');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('3', 'users.edit', 'Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ð´Ð°Ð½Ð½Ñ‹Ñ… Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'users', '2025-11-23 11:21:48');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('4', 'users.delete', 'Ð£Ð´Ð°Ð»ÐµÐ½Ð¸Ðµ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'Ð£Ð´Ð°Ð»ÐµÐ½Ð¸Ðµ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹ Ð¸Ð· ÑÐ¸ÑÑ‚ÐµÐ¼Ñ‹', 'users', '2025-11-23 11:21:48');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('5', 'users.ban', 'Ð‘Ð»Ð¾ÐºÐ¸Ñ€Ð¾Ð²ÐºÐ° Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'Ð‘Ð»Ð¾ÐºÐ¸Ñ€Ð¾Ð²ÐºÐ° Ð¸ Ñ€Ð°Ð·Ð±Ð»Ð¾ÐºÐ¸Ñ€Ð¾Ð²ÐºÐ° Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'users', '2025-11-23 11:21:48');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('6', 'roles.view', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ Ñ€Ð¾Ð»ÐµÐ¹', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ ÑÐ¿Ð¸ÑÐºÐ° Ñ€Ð¾Ð»ÐµÐ¹', 'roles', '2025-11-23 11:21:57');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('7', 'roles.create', 'Ð¡Ð¾Ð·Ð´Ð°Ð½Ð¸Ðµ Ñ€Ð¾Ð»ÐµÐ¹', 'Ð¡Ð¾Ð·Ð´Ð°Ð½Ð¸Ðµ Ð½Ð¾Ð²Ñ‹Ñ… Ñ€Ð¾Ð»ÐµÐ¹', 'roles', '2025-11-23 11:21:57');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('8', 'roles.edit', 'Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ñ€Ð¾Ð»ÐµÐ¹', 'Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ñ€Ð¾Ð»ÐµÐ¹ Ð¸ Ð¸Ñ… Ð¿Ñ€Ð°Ð²', 'roles', '2025-11-23 11:21:57');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('9', 'roles.delete', 'Ð£Ð´Ð°Ð»ÐµÐ½Ð¸Ðµ Ñ€Ð¾Ð»ÐµÐ¹', 'Ð£Ð´Ð°Ð»ÐµÐ½Ð¸Ðµ Ñ€Ð¾Ð»ÐµÐ¹ Ð¸Ð· ÑÐ¸ÑÑ‚ÐµÐ¼Ñ‹', 'roles', '2025-11-23 11:21:57');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('10', 'settings.view', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ Ð½Ð°ÑÑ‚Ñ€Ð¾ÐµÐº', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ Ð½Ð°ÑÑ‚Ñ€Ð¾ÐµÐº ÑÐ¸ÑÑ‚ÐµÐ¼Ñ‹', 'settings', '2025-11-23 11:21:57');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('11', 'settings.edit', 'Ð ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ Ð½Ð°ÑÑ‚Ñ€Ð¾ÐµÐº', 'Ð˜Ð·Ð¼ÐµÐ½ÐµÐ½Ð¸Ðµ Ð½Ð°ÑÑ‚Ñ€Ð¾ÐµÐº ÑÐ¸ÑÑ‚ÐµÐ¼Ñ‹', 'settings', '2025-11-23 11:21:57');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('12', 'emails.send', 'ÐžÑ‚Ð¿Ñ€Ð°Ð²ÐºÐ° email', 'ÐžÑ‚Ð¿Ñ€Ð°Ð²ÐºÐ° email Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑÐ¼', 'emails', '2025-11-23 11:22:10');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('13', 'emails.templates', 'Ð£Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ ÑˆÐ°Ð±Ð»Ð¾Ð½Ð°Ð¼Ð¸', 'Ð¡Ð¾Ð·Ð´Ð°Ð½Ð¸Ðµ Ð¸ Ñ€ÐµÐ´Ð°ÐºÑ‚Ð¸Ñ€Ð¾Ð²Ð°Ð½Ð¸Ðµ ÑˆÐ°Ð±Ð»Ð¾Ð½Ð¾Ð² email', 'emails', '2025-11-23 11:22:10');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('14', 'logs.view', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ Ð»Ð¾Ð³Ð¾Ð²', 'ÐŸÑ€Ð¾ÑÐ¼Ð¾Ñ‚Ñ€ Ð»Ð¾Ð³Ð¾Ð² Ð´ÐµÐ¹ÑÑ‚Ð²Ð¸Ð¹ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÐµÐ¹', 'logs', '2025-11-23 11:22:10');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('15', 'logs.delete', 'Ð£Ð´Ð°Ð»ÐµÐ½Ð¸Ðµ Ð»Ð¾Ð³Ð¾Ð²', 'Ð£Ð´Ð°Ð»ÐµÐ½Ð¸Ðµ ÑÑ‚Ð°Ñ€Ñ‹Ñ… Ð»Ð¾Ð³Ð¾Ð²', 'logs', '2025-11-23 11:22:10');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('16', 'notifications.send', 'ÐžÑ‚Ð¿Ñ€Ð°Ð²ÐºÐ° ÑƒÐ²ÐµÐ´Ð¾Ð¼Ð»ÐµÐ½Ð¸Ð¹', 'ÐžÑ‚Ð¿Ñ€Ð°Ð²ÐºÐ° ÑƒÐ²ÐµÐ´Ð¾Ð¼Ð»ÐµÐ½Ð¸Ð¹ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑÐ¼', 'notifications', '2025-11-23 11:22:10');
INSERT INTO `permissions` (`id`, `name`, `display_name`, `description`, `category`, `created_at`) VALUES ('17', 'notifications.manage', 'Ð£Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ ÑƒÐ²ÐµÐ´Ð¾Ð¼Ð»ÐµÐ½Ð¸ÑÐ¼Ð¸', 'Ð£Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ ÑÐ¸ÑÑ‚ÐµÐ¼Ð½Ñ‹Ð¼Ð¸ ÑƒÐ²ÐµÐ´Ð¾Ð¼Ð»ÐµÐ½Ð¸ÑÐ¼Ð¸', 'notifications', '2025-11-23 11:22:10');

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '1', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '2', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '3', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '4', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '5', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '6', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '7', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '8', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '9', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '10', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '11', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '12', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '13', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '14', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '15', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '16', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('1', '17', '2025-11-23 11:22:17');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '1', '2025-11-23 11:22:26');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '3', '2025-11-23 11:22:26');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '5', '2025-11-23 11:22:26');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '12', '2025-11-23 11:22:26');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '13', '2025-11-23 11:22:26');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '14', '2025-11-23 11:22:26');
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`) VALUES ('2', '16', '2025-11-23 11:22:26');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`) VALUES ('1', 'admin', 'ÐÐ´Ð¼Ð¸Ð½Ð¸ÑÑ‚Ñ€Ð°Ñ‚Ð¾Ñ€', 'ÐŸÐ¾Ð»Ð½Ñ‹Ð¹ Ð´Ð¾ÑÑ‚ÑƒÐ¿ ÐºÐ¾ Ð²ÑÐµÐ¼ Ñ„ÑƒÐ½ÐºÑ†Ð¸ÑÐ¼ ÑÐ¸ÑÑ‚ÐµÐ¼Ñ‹', '2025-11-23 11:21:39');
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`) VALUES ('2', 'moderator', 'ÐœÐ¾Ð´ÐµÑ€Ð°Ñ‚Ð¾Ñ€', 'Ð£Ð¿Ñ€Ð°Ð²Ð»ÐµÐ½Ð¸Ðµ ÐºÐ¾Ð½Ñ‚ÐµÐ½Ñ‚Ð¾Ð¼ Ð¸ Ð¿Ð¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑÐ¼Ð¸', '2025-11-23 11:21:39');
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`) VALUES ('3', 'user', 'ÐŸÐ¾Ð»ÑŒÐ·Ð¾Ð²Ð°Ñ‚ÐµÐ»ÑŒ', 'Ð‘Ð°Ð·Ð¾Ð²Ñ‹Ð¹ Ð´Ð¾ÑÑ‚ÑƒÐ¿ Ðº Ñ„ÑƒÐ½ÐºÑ†Ð¸ÑÐ¼ ÑÐ°Ð¹Ñ‚Ð°', '2025-11-23 11:21:39');

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_type` enum('string','int','bool','json') COLLATE utf8mb4_unicode_ci DEFAULT 'string',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('1', 'max_login_attempts', '5', 'int', '???????????????? ?????????????? ?????????? ???? ????????????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('2', 'lockout_duration', '15', 'int', '?????????? ???????????????????? ?? ??????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('3', 'session_timeout', '3600', 'int', '?????????????? ???????????? ?? ????????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('4', 'password_min_length', '8', 'int', '?????????????????????? ?????????? ????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('5', 'require_email_verification', '1', 'bool', '?????????????????? ?????????????????????????? email', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('6', 'allow_registration', '1', 'bool', '?????????????????? ??????????????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('7', 'maintenance_mode', '0', 'bool', '?????????? ????????????????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('8', 'site_name', 'AuraUI', 'string', '???????????????? ??????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('9', 'admin_email', '', 'string', 'Email ???????????????????????????? ?????? ??????????????????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('10', 'max_upload_size', '5', 'int', '???????????????????????? ???????????? ???????????????? ?? MB', '2025-11-25 15:19:37', '2025-11-25 15:19:37');
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `created_at`, `updated_at`) VALUES ('11', 'token_expiry_hours', '24', 'int', '???????? ???????????????? ???????????? ???????????? ???????????? ?? ??????????', '2025-11-25 15:19:37', '2025-11-25 15:19:37');

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES ('2', '3', '2025-11-23 11:22:41');
INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES ('3', '3', '2025-11-23 11:22:41');
INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES ('4', '3', '2025-11-23 11:22:41');
INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES ('5', '3', '2025-11-23 11:22:41');
INSERT INTO `user_roles` (`user_id`, `role_id`, `created_at`) VALUES ('6', '1', '2025-11-23 11:22:33');

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `session_id` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `device_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_activity` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_session` (`session_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_sessions` (`id`, `user_id`, `session_id`, `ip_address`, `user_agent`, `device_info`, `created_at`, `last_activity`, `is_active`) VALUES ('1', '6', '8ptmijv089ldf9k1ct7k0fq7co', '172.25.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', 'Windows / Chrome', '2025-11-25 15:29:06', '2025-11-25 15:29:06', '1');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `email_verification_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verification_expires` datetime DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  `failed_attempts` int DEFAULT '0',
  `locked_until` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_users_email` (`email`),
  KEY `idx_users_username` (`username`),
  KEY `idx_users_created_at` (`created_at`),
  KEY `idx_users_is_admin` (`is_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `email`, `email_verified`, `email_verification_token`, `email_verification_expires`, `avatar`, `password_hash`, `is_admin`, `created_at`, `last_login`, `failed_attempts`, `locked_until`) VALUES ('2', 'testuser', 'test@example.com', '1', NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$QUdCdEZkUTZnSjVBM0FKLg$Z/jFYXDu7KYAwZMBf5+Gd8lBM7yMSVLxWeUvhDRXgb8', '0', '2025-11-23 08:46:34', NULL, '0', NULL);
INSERT INTO `users` (`id`, `username`, `email`, `email_verified`, `email_verification_token`, `email_verification_expires`, `avatar`, `password_hash`, `is_admin`, `created_at`, `last_login`, `failed_attempts`, `locked_until`) VALUES ('3', 'testuser2', 'test2@example.com', '1', NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$U2pOOXBFTW1Sa3NydU5Wcg$gMRuq8zpEH1tp7xQqXpLfnPGdnxFZT0Nze0c/GP2PEY', '0', '2025-11-23 08:47:06', NULL, '0', NULL);
INSERT INTO `users` (`id`, `username`, `email`, `email_verified`, `email_verification_token`, `email_verification_expires`, `avatar`, `password_hash`, `is_admin`, `created_at`, `last_login`, `failed_attempts`, `locked_until`) VALUES ('4', 'testuser3', 'test3@example.com', '1', NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$S1JiSWdLSmVsTzlvakFsbg$n3QaD/peV2ki19pBbztcIw4sEctFhurLKvLK1Hy6OOs', '0', '2025-11-23 08:47:36', NULL, '0', NULL);
INSERT INTO `users` (`id`, `username`, `email`, `email_verified`, `email_verification_token`, `email_verification_expires`, `avatar`, `password_hash`, `is_admin`, `created_at`, `last_login`, `failed_attempts`, `locked_until`) VALUES ('5', 'testuser4', 'test4@example.com', '1', NULL, NULL, NULL, '$argon2id$v=19$m=65536,t=4,p=1$VmpVaTlZYnR5amtsSnR1RQ$uQvBtugyKha8bj7wwnGcUU047HfcCeGbU+1RINY7Fak', '0', '2025-11-23 08:47:47', '2025-11-23 08:48:38', '0', NULL);
INSERT INTO `users` (`id`, `username`, `email`, `email_verified`, `email_verification_token`, `email_verification_expires`, `avatar`, `password_hash`, `is_admin`, `created_at`, `last_login`, `failed_attempts`, `locked_until`) VALUES ('6', 'demiz99', 'demiz99@mail.ru', '1', NULL, NULL, 'avatar_6_1763987821.jpg', '$argon2id$v=19$m=65536,t=4,p=1$OVgud2pFamFSdWs2ZG8zUw$dJfnO6lhCxOtUUGBHU+Iwn4KfF2HiqOGKscOLHlMI+k', '1', '2025-11-23 08:55:42', '2025-11-25 15:29:06', '0', NULL);

SET FOREIGN_KEY_CHECKS=1;
