<?php
/**
 * Admin API Keys Management
 * 
 * API keys generation and management
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
    case 'list':
        listApiKeys($db);
        break;
    case 'create':
        createApiKey($db);
        break;
    case 'revoke':
        revokeApiKey($db, (int)($_POST['id'] ?? 0));
        break;
    case 'toggle':
        toggleApiKey($db, (int)($_POST['id'] ?? 0));
        break;
    case 'update':
        updateApiKey($db);
        break;
    case 'stats':
        getApiStats($db, (int)($_GET['id'] ?? 0));
        break;
    case 'usage':
        getApiUsage($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * List all API keys
 */
function listApiKeys($db): void
{
    $stmt = $db->query("
        SELECT k.*, u.username, u.email,
               (SELECT COUNT(*) FROM api_usage_stats WHERE api_key_id = k.id) as total_requests,
               (SELECT COUNT(*) FROM api_usage_stats WHERE api_key_id = k.id AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)) as requests_24h
        FROM api_keys k
        JOIN users u ON k.user_id = u.id
        ORDER BY k.created_at DESC
    ");
    
    $keys = $stmt->fetchAll();
    
    // Mask API keys
    foreach ($keys as &$key) {
        $key['api_key_masked'] = substr($key['api_key'], 0, 8) . '...' . substr($key['api_key'], -4);
    }
    
    echo json_encode(['success' => true, 'keys' => $keys]);
}

/**
 * Create new API key
 */
function createApiKey($db): void
{
    $userId = (int)($_POST['user_id'] ?? $_SESSION['user_id']);
    $name = trim($_POST['name'] ?? '');
    $rateLimit = (int)($_POST['rate_limit'] ?? 1000);
    $ratePeriod = $_POST['rate_period'] ?? 'hour';
    $expiresAt = $_POST['expires_at'] ?? null;
    $permissions = $_POST['permissions'] ?? null;
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Name required']);
        return;
    }
    
    // Generate API key and secret
    $apiKey = generateApiKey();
    $secret = generateSecret();
    $secretHash = password_hash($secret, PASSWORD_ARGON2ID);
    
    try {
        $stmt = $db->prepare("
            INSERT INTO api_keys (user_id, name, api_key, secret_hash, permissions, rate_limit, rate_period, expires_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId, $name, $apiKey, $secretHash, 
            $permissions ? json_encode($permissions) : null,
            $rateLimit, $ratePeriod, $expiresAt
        ]);
        
        echo json_encode([
            'success' => true, 
            'id' => $db->lastInsertId(),
            'api_key' => $apiKey,
            'secret' => $secret,
            'message' => 'Save the secret now! It will not be shown again.'
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Failed to create API key']);
    }
}

/**
 * Revoke (delete) API key
 */
function revokeApiKey($db, int $id): void
{
    $stmt = $db->prepare("DELETE FROM api_keys WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Toggle API key active status
 */
function toggleApiKey($db, int $id): void
{
    $stmt = $db->prepare("UPDATE api_keys SET is_active = NOT is_active WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Update API key settings
 */
function updateApiKey($db): void
{
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $rateLimit = (int)($_POST['rate_limit'] ?? 1000);
    $ratePeriod = $_POST['rate_period'] ?? 'hour';
    $expiresAt = $_POST['expires_at'] ?? null;
    
    $stmt = $db->prepare("
        UPDATE api_keys SET name = ?, rate_limit = ?, rate_period = ?, expires_at = ?
        WHERE id = ?
    ");
    $stmt->execute([$name, $rateLimit, $ratePeriod, $expiresAt, $id]);
    echo json_encode(['success' => true]);
}

/**
 * Get API key statistics
 */
function getApiStats($db, int $id): void
{
    // Get key info
    $stmt = $db->prepare("SELECT * FROM api_keys WHERE id = ?");
    $stmt->execute([$id]);
    $key = $stmt->fetch();
    
    if (!$key) {
        echo json_encode(['success' => false, 'error' => 'API key not found']);
        return;
    }
    
    // Usage by endpoint
    $stmt = $db->prepare("
        SELECT endpoint, method, COUNT(*) as count, AVG(response_time_ms) as avg_time
        FROM api_usage_stats
        WHERE api_key_id = ?
        GROUP BY endpoint, method
        ORDER BY count DESC
        LIMIT 20
    ");
    $stmt->execute([$id]);
    $byEndpoint = $stmt->fetchAll();
    
    // Usage by day
    $stmt = $db->prepare("
        SELECT DATE(created_at) as date, COUNT(*) as count
        FROM api_usage_stats
        WHERE api_key_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date
    ");
    $stmt->execute([$id]);
    $byDay = $stmt->fetchAll();
    
    // Status codes
    $stmt = $db->prepare("
        SELECT status_code, COUNT(*) as count
        FROM api_usage_stats
        WHERE api_key_id = ?
        GROUP BY status_code
    ");
    $stmt->execute([$id]);
    $byStatus = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'key' => $key,
        'by_endpoint' => $byEndpoint,
        'by_day' => $byDay,
        'by_status' => $byStatus
    ]);
}

/**
 * Get overall API usage
 */
function getApiUsage($db): void
{
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM api_keys WHERE is_active = 1) as active_keys,
            (SELECT COUNT(*) FROM api_usage_stats WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)) as requests_24h,
            (SELECT COUNT(*) FROM api_usage_stats WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)) as requests_7d,
            (SELECT AVG(response_time_ms) FROM api_usage_stats WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)) as avg_response_time
    ")->fetch();
    
    $topKeys = $db->query("
        SELECT k.name, k.api_key, COUNT(s.id) as requests
        FROM api_keys k
        LEFT JOIN api_usage_stats s ON k.id = s.api_key_id AND s.created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
        GROUP BY k.id
        ORDER BY requests DESC
        LIMIT 5
    ")->fetchAll();
    
    echo json_encode(['success' => true, 'stats' => $stats, 'top_keys' => $topKeys]);
}

/**
 * Generate random API key
 */
function generateApiKey(): string
{
    return 'ak_' . bin2hex(random_bytes(24));
}

/**
 * Generate random secret
 */
function generateSecret(): string
{
    return 'sk_' . bin2hex(random_bytes(32));
}
