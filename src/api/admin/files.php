<?php
/**
 * Admin Files API
 * 
 * File manager for uploads
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
        listFiles($db);
        break;
    case 'upload':
        uploadFile($db);
        break;
    case 'delete':
        deleteFile($db);
        break;
    case 'rename':
        renameFile($db);
        break;
    case 'get_folders':
        getFolders();
        break;
    case 'create_folder':
        createFolder();
        break;
    case 'stats':
        getStats($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * List files in directory
 */
function listFiles($db): void
{
    $folder = $_GET['folder'] ?? '';
    $folder = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $folder);
    
    // Base uploads directory
    $uploadsBase = __DIR__ . '/../../uploads';
    $basePath = $folder ? $uploadsBase . '/' . $folder : $uploadsBase;
    
    // Create uploads directory if not exists
    if (!is_dir($uploadsBase)) {
        mkdir($uploadsBase, 0755, true);
    }
    $files = [];
    
    if (is_dir($basePath)) {
        $items = scandir($basePath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            
            $fullPath = $basePath . '/' . $item;
            $isDir = is_dir($fullPath);
            
            $relativePath = $folder ? '/uploads/' . $folder . '/' . $item : '/uploads/' . $item;
            $files[] = [
                'name' => $item,
                'path' => $relativePath,
                'is_dir' => $isDir,
                'size' => $isDir ? 0 : filesize($fullPath),
                'size_formatted' => $isDir ? '-' : formatBytes(filesize($fullPath)),
                'mime' => $isDir ? 'folder' : mime_content_type($fullPath),
                'modified' => date('Y-m-d H:i:s', filemtime($fullPath)),
                'is_image' => !$isDir && in_array(strtolower(pathinfo($item, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])
            ];
        }
    }
    
    usort($files, function($a, $b) {
        if ($a['is_dir'] !== $b['is_dir']) return $b['is_dir'] - $a['is_dir'];
        return strcasecmp($a['name'], $b['name']);
    });
    
    echo json_encode(['success' => true, 'files' => $files, 'folder' => $folder]);
}

/**
 * Upload file
 */
function uploadFile($db): void
{
    if (!isset($_FILES['file'])) {
        echo json_encode(['success' => false, 'error' => 'No file uploaded']);
        return;
    }
    
    $folder = $_POST['folder'] ?? 'uploads';
    $folder = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $folder);
    
    $file = $_FILES['file'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 
                     'application/pdf', 'text/plain', 'application/json',
                     'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'File type not allowed']);
        return;
    }
    
    $maxSize = 10 * 1024 * 1024; // 10MB
    if ($file['size'] > $maxSize) {
        echo json_encode(['success' => false, 'error' => 'File too large (max 10MB)']);
        return;
    }
    
    $uploadDir = __DIR__ . '/../../uploads/' . $folder;
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = pathinfo($file['name'], PATHINFO_FILENAME);
    $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $filename);
    $newName = $filename . '_' . time() . '.' . $ext;
    $targetPath = $uploadDir . '/' . $newName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Save to database
        $stmt = $db->prepare("
            INSERT INTO media_files (filename, original_name, mime_type, file_size, path, folder, uploaded_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $newName,
            $file['name'],
            $file['type'],
            $file['size'],
            '/uploads/' . $folder . '/' . $newName,
            $folder,
            $_SESSION['user_id']
        ]);
        
        echo json_encode([
            'success' => true, 
            'file' => [
                'name' => $newName,
                'path' => '/uploads/' . $folder . '/' . $newName,
                'size' => $file['size']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to upload file']);
    }
}

/**
 * Delete file
 */
function deleteFile($db): void
{
    $path = $_POST['path'] ?? '';
    
    if (empty($path) || strpos($path, '..') !== false) {
        echo json_encode(['success' => false, 'error' => 'Invalid path']);
        return;
    }
    
    $fullPath = __DIR__ . '/../..' . $path;
    
    if (file_exists($fullPath)) {
        if (is_dir($fullPath)) {
            rmdir($fullPath);
        } else {
            unlink($fullPath);
            $db->prepare("DELETE FROM media_files WHERE path = ?")->execute([$path]);
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File not found']);
    }
}

/**
 * Rename file
 */
function renameFile($db): void
{
    $oldPath = $_POST['old_path'] ?? '';
    $newName = $_POST['new_name'] ?? '';
    
    if (empty($oldPath) || empty($newName) || strpos($oldPath, '..') !== false) {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
        return;
    }
    
    $newName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $newName);
    $fullOldPath = __DIR__ . '/../..' . $oldPath;
    $dir = dirname($fullOldPath);
    $fullNewPath = $dir . '/' . $newName;
    
    if (file_exists($fullOldPath) && !file_exists($fullNewPath)) {
        rename($fullOldPath, $fullNewPath);
        
        $newPath = dirname($oldPath) . '/' . $newName;
        $db->prepare("UPDATE media_files SET filename = ?, path = ? WHERE path = ?")
           ->execute([$newName, $newPath, $oldPath]);
        
        echo json_encode(['success' => true, 'new_path' => $newPath]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Cannot rename file']);
    }
}

/**
 * Get folder list
 */
function getFolders(): void
{
    $basePath = __DIR__ . '/../../uploads';
    $folders = [''];  // Root uploads folder
    
    // Create uploads directory if not exists
    if (!is_dir($basePath)) {
        mkdir($basePath, 0755, true);
    }
    
    if (is_dir($basePath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($basePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                $relativePath = str_replace([$basePath . '/', $basePath . '\\'], '', $item->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);
                $folders[] = $relativePath;
            }
        }
    }
    
    echo json_encode(['success' => true, 'folders' => $folders]);
}

/**
 * Create folder
 */
function createFolder(): void
{
    $parent = $_POST['parent'] ?? '';
    $name = $_POST['name'] ?? '';
    
    $name = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
    if (empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Invalid folder name']);
        return;
    }
    
    $basePath = __DIR__ . '/../../uploads';
    $path = $parent ? $basePath . '/' . $parent . '/' . $name : $basePath . '/' . $name;
    
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Folder already exists']);
    }
}

/**
 * Get storage stats
 */
function getStats($db): void
{
    $basePath = __DIR__ . '/../../uploads';
    $totalSize = 0;
    $fileCount = 0;
    
    if (is_dir($basePath)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($basePath));
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $totalSize += $file->getSize();
                $fileCount++;
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'total_size' => $totalSize,
            'total_size_formatted' => formatBytes($totalSize),
            'file_count' => $fileCount
        ]
    ]);
}

/**
 * Format bytes to human readable
 */
function formatBytes(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
