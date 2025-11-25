<?php
/**
 * Admin System API
 * 
 * System monitoring and status
 * 
 * @package AuraUI\API\Admin
 */

require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Access denied']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db = getDB();

switch ($action) {
    case 'status':
        getSystemStatus($db);
        break;
    case 'disk':
        getDiskUsage();
        break;
    case 'services':
        getServicesStatus($db);
        break;
    case 'php_info':
        getPhpInfo();
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * Get overall system status
 */
function getSystemStatus($db): void
{
    $status = [
        'server_time' => date('Y-m-d H:i:s'),
        'timezone' => date_default_timezone_get(),
        'php_version' => PHP_VERSION,
        'os' => PHP_OS,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? '',
        'uptime' => getUptime(),
        'memory' => getMemoryUsage(),
        'load_average' => getLoadAverage()
    ];
    
    echo json_encode(['success' => true, 'status' => $status]);
}

/**
 * Get disk usage
 */
function getDiskUsage(): void
{
    $path = '/';
    
    $total = disk_total_space($path);
    $free = disk_free_space($path);
    $used = $total - $free;
    
    // Get uploads folder size
    $uploadsPath = __DIR__ . '/../../uploads';
    $uploadsSize = getFolderSize($uploadsPath);
    
    echo json_encode([
        'success' => true,
        'disk' => [
            'total' => $total,
            'total_formatted' => formatBytes($total),
            'free' => $free,
            'free_formatted' => formatBytes($free),
            'used' => $used,
            'used_formatted' => formatBytes($used),
            'used_percent' => round(($used / $total) * 100, 1),
            'uploads_size' => $uploadsSize,
            'uploads_formatted' => formatBytes($uploadsSize)
        ]
    ]);
}

/**
 * Get services status
 */
function getServicesStatus($db): void
{
    $services = [];
    
    // MySQL
    try {
        $db->query("SELECT 1");
        $mysqlVersion = $db->query("SELECT VERSION()")->fetchColumn();
        $services['mysql'] = [
            'status' => 'running',
            'version' => $mysqlVersion,
            'message' => 'Connected'
        ];
    } catch (Exception $e) {
        $services['mysql'] = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
    
    // Redis
    try {
        $redis = new Redis();
        $redisHost = getenv('REDIS_HOST') ?: 'redis';
        $redisPort = getenv('REDIS_PORT') ?: 6379;
        
        if ($redis->connect($redisHost, $redisPort, 2)) {
            $info = $redis->info();
            $services['redis'] = [
                'status' => 'running',
                'version' => $info['redis_version'] ?? 'Unknown',
                'memory' => $info['used_memory_human'] ?? 'Unknown',
                'clients' => $info['connected_clients'] ?? 0,
                'message' => 'Connected'
            ];
        }
    } catch (Exception $e) {
        $services['redis'] = [
            'status' => 'stopped',
            'message' => 'Not available'
        ];
    }
    
    // Apache
    $services['apache'] = [
        'status' => 'running',
        'version' => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache',
        'message' => 'Serving requests'
    ];
    
    echo json_encode(['success' => true, 'services' => $services]);
}

/**
 * Get PHP info summary
 */
function getPhpInfo(): void
{
    $info = [
        'version' => PHP_VERSION,
        'sapi' => PHP_SAPI,
        'extensions' => get_loaded_extensions(),
        'memory_limit' => ini_get('memory_limit'),
        'max_execution_time' => ini_get('max_execution_time'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'display_errors' => ini_get('display_errors'),
        'error_reporting' => ini_get('error_reporting'),
        'date_timezone' => ini_get('date.timezone'),
        'opcache_enabled' => function_exists('opcache_get_status') && opcache_get_status() !== false
    ];
    
    echo json_encode(['success' => true, 'info' => $info]);
}

/**
 * Get server uptime
 */
function getUptime(): string
{
    if (file_exists('/proc/uptime')) {
        $uptime = file_get_contents('/proc/uptime');
        $seconds = (int)explode(' ', $uptime)[0];
        
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        return "{$days}d {$hours}h {$minutes}m";
    }
    return 'Unknown';
}

/**
 * Get memory usage
 */
function getMemoryUsage(): array
{
    $memInfo = [];
    
    if (file_exists('/proc/meminfo')) {
        $data = file_get_contents('/proc/meminfo');
        preg_match_all('/(\w+):\s+(\d+)/', $data, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $memInfo[$match[1]] = (int)$match[2] * 1024; // Convert to bytes
        }
        
        $total = $memInfo['MemTotal'] ?? 0;
        $free = $memInfo['MemFree'] ?? 0;
        $available = $memInfo['MemAvailable'] ?? $free;
        $used = $total - $available;
        
        return [
            'total' => formatBytes($total),
            'used' => formatBytes($used),
            'free' => formatBytes($available),
            'percent' => $total > 0 ? round(($used / $total) * 100, 1) : 0
        ];
    }
    
    return ['total' => 'Unknown', 'used' => 'Unknown', 'free' => 'Unknown', 'percent' => 0];
}

/**
 * Get load average
 */
function getLoadAverage(): array
{
    if (function_exists('sys_getloadavg')) {
        $load = sys_getloadavg();
        return [
            '1min' => round($load[0], 2),
            '5min' => round($load[1], 2),
            '15min' => round($load[2], 2)
        ];
    }
    return ['1min' => 0, '5min' => 0, '15min' => 0];
}

/**
 * Get folder size recursively
 */
function getFolderSize(string $path): int
{
    $size = 0;
    if (is_dir($path)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
    }
    return $size;
}

/**
 * Format bytes
 */
function formatBytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
