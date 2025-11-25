<?php
/**
 * Admin CMS API
 * 
 * Pages and menus management
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
    // Pages
    case 'get_pages':
        getPages($db);
        break;
    case 'get_page':
        getPage($db, (int)($_GET['id'] ?? 0));
        break;
    case 'create_page':
        createPage($db);
        break;
    case 'update_page':
        updatePage($db);
        break;
    case 'delete_page':
        deletePage($db, (int)($_POST['id'] ?? 0));
        break;
    // Menus
    case 'get_menus':
        getMenus($db);
        break;
    case 'get_menu_items':
        getMenuItems($db, (int)($_GET['menu_id'] ?? 0));
        break;
    case 'create_menu':
        createMenu($db);
        break;
    case 'create_menu_item':
        createMenuItem($db);
        break;
    case 'update_menu_item':
        updateMenuItem($db);
        break;
    case 'delete_menu_item':
        deleteMenuItem($db, (int)($_POST['id'] ?? 0));
        break;
    case 'reorder_menu':
        reorderMenu($db);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
}

/**
 * Get all pages
 */
function getPages($db): void
{
    $status = $_GET['status'] ?? '';
    
    $sql = "SELECT p.*, u.username as author_name FROM cms_pages p LEFT JOIN users u ON p.author_id = u.id";
    $params = [];
    
    if (!empty($status)) {
        $sql .= " WHERE p.status = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY p.sort_order, p.title";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    echo json_encode(['success' => true, 'pages' => $stmt->fetchAll()]);
}

/**
 * Get single page
 */
function getPage($db, int $id): void
{
    $stmt = $db->prepare("SELECT * FROM cms_pages WHERE id = ?");
    $stmt->execute([$id]);
    $page = $stmt->fetch();
    
    if (!$page) {
        echo json_encode(['success' => false, 'error' => 'Page not found']);
        return;
    }
    
    echo json_encode(['success' => true, 'page' => $page]);
}

/**
 * Create page
 */
function createPage($db): void
{
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    $metaTitle = trim($_POST['meta_title'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $metaKeywords = trim($_POST['meta_keywords'] ?? '');
    
    if (empty($title)) {
        echo json_encode(['success' => false, 'error' => 'Title required']);
        return;
    }
    
    // Generate slug if empty
    if (empty($slug)) {
        $slug = generateSlug($title);
    }
    
    try {
        $stmt = $db->prepare("
            INSERT INTO cms_pages (slug, title, content, meta_title, meta_description, meta_keywords, status, author_id, published_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $slug, $title, $content, $metaTitle, $metaDescription, $metaKeywords, 
            $status, $_SESSION['user_id'],
            $status === 'published' ? date('Y-m-d H:i:s') : null
        ]);
        
        echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Slug already exists']);
    }
}

/**
 * Update page
 */
function updatePage($db): void
{
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'draft';
    $metaTitle = trim($_POST['meta_title'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $metaKeywords = trim($_POST['meta_keywords'] ?? '');
    
    if ($id <= 0 || empty($title)) {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
        return;
    }
    
    // Check if status changed to published
    $stmt = $db->prepare("SELECT status FROM cms_pages WHERE id = ?");
    $stmt->execute([$id]);
    $oldStatus = $stmt->fetchColumn();
    
    $publishedAt = null;
    if ($status === 'published' && $oldStatus !== 'published') {
        $publishedAt = date('Y-m-d H:i:s');
    }
    
    try {
        $sql = "UPDATE cms_pages SET title = ?, slug = ?, content = ?, meta_title = ?, meta_description = ?, meta_keywords = ?, status = ?";
        $params = [$title, $slug, $content, $metaTitle, $metaDescription, $metaKeywords, $status];
        
        if ($publishedAt) {
            $sql .= ", published_at = ?";
            $params[] = $publishedAt;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Slug already exists']);
    }
}

/**
 * Delete page
 */
function deletePage($db, int $id): void
{
    $stmt = $db->prepare("DELETE FROM cms_pages WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Get all menus
 */
function getMenus($db): void
{
    $stmt = $db->query("
        SELECT m.*, (SELECT COUNT(*) FROM cms_menu_items WHERE menu_id = m.id) as items_count
        FROM cms_menus m ORDER BY m.name
    ");
    echo json_encode(['success' => true, 'menus' => $stmt->fetchAll()]);
}

/**
 * Get menu items
 */
function getMenuItems($db, int $menuId): void
{
    $stmt = $db->prepare("
        SELECT mi.*, p.title as page_title
        FROM cms_menu_items mi
        LEFT JOIN cms_pages p ON mi.page_id = p.id
        WHERE mi.menu_id = ?
        ORDER BY mi.sort_order
    ");
    $stmt->execute([$menuId]);
    echo json_encode(['success' => true, 'items' => $stmt->fetchAll()]);
}

/**
 * Create menu
 */
function createMenu($db): void
{
    $name = trim($_POST['name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    
    if (empty($name) || empty($location)) {
        echo json_encode(['success' => false, 'error' => 'Name and location required']);
        return;
    }
    
    $stmt = $db->prepare("INSERT INTO cms_menus (name, location) VALUES (?, ?)");
    $stmt->execute([$name, $location]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}

/**
 * Create menu item
 */
function createMenuItem($db): void
{
    $menuId = (int)($_POST['menu_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $pageId = (int)($_POST['page_id'] ?? 0) ?: null;
    $icon = trim($_POST['icon'] ?? '') ?: null;
    
    if ($menuId <= 0 || empty($title)) {
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
        return;
    }
    
    // Get max sort order
    $stmt = $db->prepare("SELECT MAX(sort_order) FROM cms_menu_items WHERE menu_id = ?");
    $stmt->execute([$menuId]);
    $maxOrder = (int)$stmt->fetchColumn();
    
    $stmt = $db->prepare("
        INSERT INTO cms_menu_items (menu_id, title, url, page_id, icon, sort_order)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$menuId, $title, $url, $pageId, $icon, $maxOrder + 1]);
    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
}

/**
 * Update menu item
 */
function updateMenuItem($db): void
{
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['url'] ?? '');
    $pageId = (int)($_POST['page_id'] ?? 0) ?: null;
    $icon = trim($_POST['icon'] ?? '') ?: null;
    $isActive = (int)($_POST['is_active'] ?? 1);
    
    $stmt = $db->prepare("
        UPDATE cms_menu_items SET title = ?, url = ?, page_id = ?, icon = ?, is_active = ?
        WHERE id = ?
    ");
    $stmt->execute([$title, $url, $pageId, $icon, $isActive, $id]);
    echo json_encode(['success' => true]);
}

/**
 * Delete menu item
 */
function deleteMenuItem($db, int $id): void
{
    $stmt = $db->prepare("DELETE FROM cms_menu_items WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

/**
 * Reorder menu items
 */
function reorderMenu($db): void
{
    $items = json_decode($_POST['items'] ?? '[]', true);
    
    $stmt = $db->prepare("UPDATE cms_menu_items SET sort_order = ? WHERE id = ?");
    foreach ($items as $order => $id) {
        $stmt->execute([$order, (int)$id]);
    }
    
    echo json_encode(['success' => true]);
}

/**
 * Generate URL slug
 */
function generateSlug(string $text): string
{
    $transliteration = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
        'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '',
        'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
    ];
    
    $text = mb_strtolower($text);
    $text = strtr($text, $transliteration);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');
    
    return $text;
}
