<?php

// Защита от повторного подключения
if (defined('APP_CONFIG_LOADED')) {
    return;
}
define('APP_CONFIG_LOADED', true);

// Подключаем autoload только один раз
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Подключаем классы для улучшений (из папки core)
require_once __DIR__ . '/core/RateLimiter.php';
require_once __DIR__ . '/core/Logger.php';
require_once __DIR__ . '/core/Paginator.php';
require_once __DIR__ . '/core/Cache.php';
require_once __DIR__ . '/core/QueryCache.php';
require_once __DIR__ . '/core/BatchProcessor.php';

// Подключаем дополнительные классы оптимизации (из папки helpers)
require_once __DIR__ . '/helpers/ImageOptimizer.php';
require_once __DIR__ . '/helpers/Minifier.php';
require_once __DIR__ . '/helpers/CDN.php';
require_once __DIR__ . '/helpers/ActivityLogger.php';
require_once __DIR__ . '/helpers/NotificationManager.php';
require_once __DIR__ . '/helpers/ImageUploader.php';

// Создаем алиасы для обратной совместимости (для использования в views без namespace)
class_alias('AuraUI\Helpers\CDN', 'CDN');
class_alias('AuraUI\Helpers\Minifier', 'Minifier');
class_alias('AuraUI\Helpers\ImageOptimizer', 'ImageOptimizer');
class_alias('AuraUI\Helpers\NotificationManager', 'NotificationManager');
class_alias('AuraUI\Helpers\ActivityLogger', 'ActivityLogger');
class_alias('AuraUI\Helpers\ImageUploader', 'ImageUploader');
class_alias('AuraUI\Helpers\ActivityActions', 'ActivityActions');

// Настройки безопасности сессии (до session_start)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Поставить 1 если используете HTTPS
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');
    session_start();
}

// Подключение к БД
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'app_db');
define('DB_USER', getenv('DB_USER') ?: 'app_user');
define('DB_PASS', getenv('DB_PASS') ?: 'app_password');

// Настройки безопасности
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 минут в секундах

// Версия статических ресурсов (автоматическая на основе времени изменения файлов)
function getAssetVersion()
{
    static $version = null;

    if ($version !== null) {
        return $version;
    }

    $files = [
        __DIR__ . '/assets/css/style.css',
        __DIR__ . '/assets/css/loader.css',
        __DIR__ . '/assets/js/app.js',
        __DIR__ . '/assets/js/loader.js'
    ];

    $latestTime = 0;
    foreach ($files as $file) {
        if (file_exists($file)) {
            $mtime = filemtime($file);
            if ($mtime > $latestTime) {
                $latestTime = $mtime;
            }
        }
    }

    // Используем timestamp последнего изменения как версию
    $version = $latestTime > 0 ? $latestTime : time();
    return $version;
}

define('ASSET_VERSION', getAssetVersion());

// Resend API
define('RESEND_API_KEY', getenv('RESEND_API_KEY') ?: 're_brMPxT9m_BEgFoPQucTe22E1QcAw5svTH');
define('FROM_EMAIL', getenv('FROM_EMAIL') ?: 'onboarding@resend.dev');
define('FROM_NAME', getenv('FROM_NAME') ?: 'Мой сайт');

function getDB()
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true, // Connection pooling
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                    PDO::ATTR_TIMEOUT => 5
                ]
            );

            // Явно устанавливаем кодировку после подключения
            $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("SET CHARACTER SET utf8mb4");

        } catch (PDOException $e) {
            die("Ошибка подключения к БД");
        }
    }
    return $pdo;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: /login');
        exit;
    }
}

function isAdmin()
{
    if (!isLoggedIn()) {
        return false;
    }

    $db = getDB();
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Явно приводим к boolean, так как MySQL может вернуть 0/1 как строку
    return $user && (bool)$user['is_admin'];
}

function requireAdmin()
{
    if (!isLoggedIn()) {
        header('Location: /login');
        exit;
    }

    // Получаем данные пользователя один раз
    $db = getDB();
    $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    // Проверяем права
    if (!$user || !$user['is_admin']) {
        die('<html><head><title>Доступ запрещен</title></head><body style="font-family: Arial; text-align: center; padding: 50px;">
            <h1>🚫 Доступ запрещен</h1>
            <p>У вас нет прав администратора для доступа к этой странице.</p>
            <p><a href="/">Вернуться на главную</a></p>
        </body></html>');
    }
}

function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateCSRFToken()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
