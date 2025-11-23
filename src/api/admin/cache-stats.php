<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers/CacheHelper.php';

header('Content-Type: application/json');

// Проверка авторизации
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Не авторизован']);
    exit;
}

// Проверка прав админа
if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Доступ запрещен']);
    exit;
}

$stats = CacheHelper::getOPcacheStats();

if ($stats) {
    $memory = $stats['memory_usage'];
    $statistics = $stats['opcache_statistics'];
    
    echo json_encode([
        'success' => true,
        'data' => [
            'enabled' => $stats['opcache_enabled'],
            'memory_used' => round($memory['used_memory'] / 1024 / 1024, 2) . ' MB',
            'memory_free' => round($memory['free_memory'] / 1024 / 1024, 2) . ' MB',
            'memory_wasted' => round($memory['wasted_memory'] / 1024 / 1024, 2) . ' MB',
            'cached_scripts' => $statistics['num_cached_scripts'],
            'hits' => $statistics['hits'],
            'misses' => $statistics['misses'],
            'hit_rate' => round($statistics['opcache_hit_rate'], 2) . '%'
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'OPcache не доступен'
    ]);
}
