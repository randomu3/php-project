<?php
/**
 * Admin Logs API
 * 
 * System logs management
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
$db->exec("SET NAMES utf8mb4");

switch ($action) {
    case 'list':
        listLogs($db);
        break;
    case 'php_errors':
        getPhpErrors();
        break;
    case 'clear':
        clearLogs($db);
        break;
    case 'export':
        exportLogs($db);
        break;
    case 'stats':
        getLogStats($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * List system logs
 */
function listLogs($db): void
{
    $level = $_GET['level'] ?? '';
    $channel = $_GET['channel'] ?? '';
    $limit = min((int)($_GET['limit'] ?? 100), 500);
    $offset = (int)($_GET['offset'] ?? 0);
    
    $sql = "SELECT * FROM system_logs WHERE 1=1";
    $params = [];
    
    if (!empty($level)) {
        $sql .= " AND level = ?";
        $params[] = $level;
    }
    
    if (!empty($channel)) {
        $sql .= " AND channel = ?";
        $params[] = $channel;
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    $logs = $stmt->fetchAll();
    
    // Get total count
    $countSql = "SELECT COUNT(*) FROM system_logs WHERE 1=1";
    $countParams = [];
    if (!empty($level)) {
        $countSql .= " AND level = ?";
        $countParams[] = $level;
    }
    if (!empty($channel)) {
        $countSql .= " AND channel = ?";
        $countParams[] = $channel;
    }
    
    $stmt = $db->prepare($countSql);
    $stmt->execute($countParams);
    $total = $stmt->fetchColumn();
    
    echo json_encode(['success' => true, 'logs' => $logs, 'total' => $total]);
}

/**
 * Get PHP error log
 */
function getPhpErrors(): void
{
    $lines = (int)($_GET['lines'] ?? 100);
    $logFile = '/var/log/apache2/error.log';
    
    // Try different log locations
    $possiblePaths = [
        '/var/log/apache2/error.log',
        '/var/log/httpd/error_log',
        '/var/log/php_errors.log',
        ini_get('error_log')
    ];
    
    $content = '';
    $foundPath = '';
    
    foreach ($possiblePaths as $path) {
        if (!empty($path) && file_exists($path) && is_readable($path)) {
            $foundPath = $path;
            break;
        }
    }
    
    if (!empty($foundPath)) {
        // Read last N lines
        $file = new SplFileObject($foundPath, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();
        
        $startLine = max(0, $totalLines - $lines);
        $file->seek($startLine);
        
        $errors = [];
        while (!$file->eof()) {
            $line = $file->fgets();
            if (!empty(trim($line))) {
                $errors[] = parsePHPError($line);
            }
        }
        
        echo json_encode([
            'success' => true, 
            'errors' => array_reverse($errors),
            'log_file' => $foundPath,
            'total_lines' => $totalLines
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Log file not found or not readable']);
    }
}

/**
 * Parse PHP error line
 */
function parsePHPError(string $line): array
{
    $level = 'info';
    if (stripos($line, 'fatal') !== false) $level = 'critical';
    elseif (stripos($line, 'error') !== false) $level = 'error';
    elseif (stripos($line, 'warning') !== false) $level = 'warning';
    elseif (stripos($line, 'notice') !== false) $level = 'info';
    elseif (stripos($line, 'deprecated') !== false) $level = 'warning';
    
    // Try to extract date
    $date = null;
    if (preg_match('/\[([^\]]+)\]/', $line, $matches)) {
        $date = $matches[1];
    }
    
    return [
        'message' => trim($line),
        'level' => $level,
        'date' => $date
    ];
}

/**
 * Clear old logs
 */
function clearLogs($db): void
{
    $days = (int)($_POST['days'] ?? 30);
    $level = $_POST['level'] ?? '';
    
    $sql = "DELETE FROM system_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
    $params = [$days];
    
    if (!empty($level)) {
        $sql .= " AND level = ?";
        $params[] = $level;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    $deleted = $stmt->rowCount();
    
    echo json_encode(['success' => true, 'deleted' => $deleted]);
}

/**
 * Export logs
 */
function exportLogs($db): void
{
    $level = $_GET['level'] ?? '';
    $days = (int)($_GET['days'] ?? 7);
    
    $sql = "SELECT * FROM system_logs WHERE created_at > DATE_SUB(NOW(), INTERVAL ? DAY)";
    $params = [$days];
    
    if (!empty($level)) {
        $sql .= " AND level = ?";
        $params[] = $level;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $logs = $stmt->fetchAll();
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="logs_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Level', 'Channel', 'Message', 'File', 'Line', 'IP', 'Created At']);
    
    foreach ($logs as $log) {
        fputcsv($output, [
            $log['id'],
            $log['level'],
            $log['channel'],
            $log['message'],
            $log['file'],
            $log['line'],
            $log['ip_address'],
            $log['created_at']
        ]);
    }
    
    fclose($output);
    exit;
}

/**
 * Get log statistics
 */
function getLogStats($db): void
{
    $stats = $db->query("
        SELECT 
            level,
            COUNT(*) as count,
            MAX(created_at) as last_occurrence
        FROM system_logs
        GROUP BY level
    ")->fetchAll();
    
    $byChannel = $db->query("
        SELECT channel, COUNT(*) as count
        FROM system_logs
        GROUP BY channel
        ORDER BY count DESC
        LIMIT 10
    ")->fetchAll();
    
    $byDay = $db->query("
        SELECT DATE(created_at) as date, COUNT(*) as count
        FROM system_logs
        WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date
    ")->fetchAll();
    
    echo json_encode([
        'success' => true,
        'by_level' => $stats,
        'by_channel' => $byChannel,
        'by_day' => $byDay
    ]);
}
