<?php

/**
 * Admin Settings API
 *
 * Handles email templates, system settings, backups.
 *
 * @package AuraUI\API\Admin
 */

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Доступ запрещён']);
    exit;
}

$action = $_REQUEST['action'] ?? '';

try {
    $db = getDB();
    $db->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    switch ($action) {
        // Получить все email шаблоны
        case 'get_templates':
            $stmt = $db->query("SELECT * FROM email_templates ORDER BY name");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;
            
        // Получить один шаблон
        case 'get_template':
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $db->prepare("SELECT * FROM email_templates WHERE id = ?");
            $stmt->execute([$id]);
            $template = $stmt->fetch();
            
            if ($template) {
                echo json_encode(['success' => true, 'data' => $template]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Шаблон не найден']);
            }
            break;
            
        // Сохранить шаблон
        case 'save_template':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $id = (int)($_POST['id'] ?? 0);
            $name = sanitizeInput($_POST['name'] ?? '');
            $subject = sanitizeInput($_POST['subject'] ?? '');
            $body = $_POST['body'] ?? ''; // HTML не санитизируем
            $description = sanitizeInput($_POST['description'] ?? '');
            
            if (empty($name) || empty($subject) || empty($body)) {
                echo json_encode(['success' => false, 'error' => 'Заполните все обязательные поля']);
                exit;
            }
            
            if ($id > 0) {
                // Обновление
                $stmt = $db->prepare("UPDATE email_templates SET name = ?, subject = ?, body = ?, description = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$name, $subject, $body, $description, $id]);
            } else {
                // Создание
                $stmt = $db->prepare("INSERT INTO email_templates (name, subject, body, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $subject, $body, $description]);
                $id = $db->lastInsertId();
            }
            
            echo json_encode(['success' => true, 'message' => 'Шаблон сохранён', 'id' => $id]);
            break;
            
        // Удалить шаблон
        case 'delete_template':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $db->prepare("DELETE FROM email_templates WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode(['success' => true, 'message' => 'Шаблон удалён']);
            break;
            
        // Создать резервную копию БД
        case 'backup_db':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $backupDir = __DIR__ . '/../../backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . '/' . $filename;
            
            // Экспорт через mysqldump
            $host = DB_HOST;
            $user = DB_USER;
            $pass = DB_PASS;
            $dbname = DB_NAME;
            
            $command = "mysqldump -h{$host} -u{$user} -p{$pass} {$dbname} > {$filepath} 2>&1";
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($filepath)) {
                $size = round(filesize($filepath) / 1024, 2);
                echo json_encode([
                    'success' => true, 
                    'message' => "Резервная копия создана: {$filename} ({$size} KB)"
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Ошибка создания резервной копии']);
            }
            break;
            
        // Список резервных копий
        case 'get_backups':
            $backupDir = __DIR__ . '/../../backups';
            $backups = [];
            
            if (is_dir($backupDir)) {
                $files = glob($backupDir . '/backup_*.sql');
                foreach ($files as $file) {
                    $backups[] = [
                        'name' => basename($file),
                        'size' => round(filesize($file) / 1024, 2),
                        'date' => date('Y-m-d H:i:s', filemtime($file))
                    ];
                }
                usort($backups, fn($a, $b) => strcmp($b['date'], $a['date']));
            }
            
            echo json_encode(['success' => true, 'data' => $backups]);
            break;
            
        // Отправить тестовое письмо админу
        case 'test_email':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $stmt = $db->prepare("SELECT email FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $adminEmail = $stmt->fetchColumn();
            
            $result = sendEmail($adminEmail, 'Тестовое письмо - AuraUI Admin', 
                '<h2>Тестовое письмо</h2><p>Если вы видите это сообщение, email работает корректно.</p><p>Время: ' . date('Y-m-d H:i:s') . '</p>'
            );
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => "Тестовое письмо отправлено на {$adminEmail}"]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Ошибка отправки письма']);
            }
            break;
            
        // Получить системные настройки
        case 'get_settings':
            $stmt = $db->query("SELECT setting_key, setting_value, setting_type FROM system_settings");
            $rows = $stmt->fetchAll();
            
            $settings = [];
            foreach ($rows as $row) {
                $value = $row['setting_value'];
                if ($row['setting_type'] === 'int') $value = (int)$value;
                if ($row['setting_type'] === 'bool') $value = $value;
                if ($row['setting_type'] === 'json') $value = json_decode($value, true);
                $settings[$row['setting_key']] = $value;
            }
            
            echo json_encode(['success' => true, 'data' => $settings]);
            break;
            
        // Сохранить системные настройки
        case 'save_settings':
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                echo json_encode(['success' => false, 'error' => 'Ошибка безопасности']);
                exit;
            }
            
            $settingsMap = [
                'max_login_attempts' => 'int',
                'lockout_duration' => 'int',
                'session_timeout' => 'int',
                'password_min_length' => 'int',
                'require_email_verification' => 'bool',
                'allow_registration' => 'bool',
                'maintenance_mode' => 'bool'
            ];
            
            foreach ($settingsMap as $key => $type) {
                if (isset($_POST[$key])) {
                    $value = $type === 'bool' ? '1' : (string)$_POST[$key];
                } else {
                    $value = $type === 'bool' ? '0' : null;
                }
                
                if ($value !== null) {
                    $stmt = $db->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->execute([$value, $key]);
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Настройки сохранены']);
            break;
            
        // Получить одну настройку
        case 'get_setting':
            $key = $_GET['key'] ?? '';
            $stmt = $db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $value = $stmt->fetchColumn();
            
            echo json_encode(['success' => true, 'value' => $value]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Неизвестное действие']);
    }
    
} catch (PDOException $e) {
    error_log("Admin settings API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ошибка базы данных']);
}
