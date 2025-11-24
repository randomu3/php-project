# CDN

**Файл**: `/var/www/html/helpers/CDN.php`

**Категория**: Helpers

## Описание

CDN - интеграция с Content Delivery Network

## Методы

### `init($cdnUrl = null, $enabled = null)`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN

---

### `asset($path)`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)

---

### `image($path)`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения

---

### `css($path)`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения
    /
    public static function image($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для CSS

---

### `js($path)`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения
    /
    public static function image($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для CSS
    /
    public static function css($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для JS

---

### `isEnabled()`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения
    /
    public static function image($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для CSS
    /
    public static function css($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для JS
    /
    public static function js($path) {
        return self::asset($path);
    }
    
    /**
    Проверить включен ли CDN

---

### `getPopularCDNs()`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения
    /
    public static function image($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для CSS
    /
    public static function css($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для JS
    /
    public static function js($path) {
        return self::asset($path);
    }
    
    /**
    Проверить включен ли CDN
    /
    public static function isEnabled() {
        return self::$enabled;
    }
    
    /**
    Получить список популярных бесплатных CDN

---

### `preconnectTags()`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения
    /
    public static function image($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для CSS
    /
    public static function css($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для JS
    /
    public static function js($path) {
        return self::asset($path);
    }
    
    /**
    Проверить включен ли CDN
    /
    public static function isEnabled() {
        return self::$enabled;
    }
    
    /**
    Получить список популярных бесплатных CDN
    /
    public static function getPopularCDNs() {
        return [
            'cloudflare' => [
                'name' => 'Cloudflare',
                'url' => 'https://www.cloudflare.com',
                'free' => true,
                'features' => ['Unlimited bandwidth', 'DDoS protection', 'SSL', 'Cache'],
                'setup' => 'Change nameservers to Cloudflare'
            ],
            'jsdelivr' => [
                'name' => 'jsDelivr',
                'url' => 'https://www.jsdelivr.com',
                'free' => true,
                'features' => ['NPM packages', 'GitHub repos', 'WordPress plugins'],
                'setup' => 'Use: https://cdn.jsdelivr.net/gh/user/repo@version/file'
            ],
            'unpkg' => [
                'name' => 'unpkg',
                'url' => 'https://unpkg.com',
                'free' => true,
                'features' => ['NPM packages', 'Fast', 'Simple'],
                'setup' => 'Use: https://unpkg.com/package@version/file'
            ],
            'bunny' => [
                'name' => 'BunnyCDN',
                'url' => 'https://bunny.net',
                'free' => false,
                'price' => '$1/TB',
                'features' => ['Fast', 'Cheap', 'Easy setup', 'Storage'],
                'setup' => 'Create pull zone, point to your origin'
            ]
        ];
    }
    
    /**
    Генерировать HTML для preconnect к CDN

---

### `sync($directory, $cdnConfig)`

CDN - интеграция с Content Delivery Network
/
class CDN {
    private static $enabled = false;
    private static $cdnUrl = '';
    private static $localUrl = '';
    
    /**
    Инициализация CDN
    /
    public static function init($cdnUrl = null, $enabled = null) {
        self::$cdnUrl = $cdnUrl ?? getenv('CDN_URL') ?? '';
        self::$enabled = $enabled ?? (getenv('CDN_ENABLED') === 'true');
        self::$localUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    
    /**
    Получить URL для ассета (с CDN или локально)
    /
    public static function asset($path) {
        // Убираем начальный слеш если есть
        $path = ltrim($path, '/');
        
        // Если CDN включен - используем CDN URL
        if (self::$enabled && !empty(self::$cdnUrl)) {
            return self::$cdnUrl . '/' . $path;
        }
        
        // Иначе локальный URL
        return '/' . $path;
    }
    
    /**
    Получить URL для изображения
    /
    public static function image($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для CSS
    /
    public static function css($path) {
        return self::asset($path);
    }
    
    /**
    Получить URL для JS
    /
    public static function js($path) {
        return self::asset($path);
    }
    
    /**
    Проверить включен ли CDN
    /
    public static function isEnabled() {
        return self::$enabled;
    }
    
    /**
    Получить список популярных бесплатных CDN
    /
    public static function getPopularCDNs() {
        return [
            'cloudflare' => [
                'name' => 'Cloudflare',
                'url' => 'https://www.cloudflare.com',
                'free' => true,
                'features' => ['Unlimited bandwidth', 'DDoS protection', 'SSL', 'Cache'],
                'setup' => 'Change nameservers to Cloudflare'
            ],
            'jsdelivr' => [
                'name' => 'jsDelivr',
                'url' => 'https://www.jsdelivr.com',
                'free' => true,
                'features' => ['NPM packages', 'GitHub repos', 'WordPress plugins'],
                'setup' => 'Use: https://cdn.jsdelivr.net/gh/user/repo@version/file'
            ],
            'unpkg' => [
                'name' => 'unpkg',
                'url' => 'https://unpkg.com',
                'free' => true,
                'features' => ['NPM packages', 'Fast', 'Simple'],
                'setup' => 'Use: https://unpkg.com/package@version/file'
            ],
            'bunny' => [
                'name' => 'BunnyCDN',
                'url' => 'https://bunny.net',
                'free' => false,
                'price' => '$1/TB',
                'features' => ['Fast', 'Cheap', 'Easy setup', 'Storage'],
                'setup' => 'Create pull zone, point to your origin'
            ]
        ];
    }
    
    /**
    Генерировать HTML для preconnect к CDN
    /
    public static function preconnectTags() {
        if (!self::$enabled || empty(self::$cdnUrl)) {
            return '';
        }
        
        $domain = parse_url(self::$cdnUrl, PHP_URL_HOST);
        
        return sprintf(
            '<link rel="dns-prefetch" href="//%s">' . "\n" .
            '<link rel="preconnect" href="%s" crossorigin>',
            $domain,
            self::$cdnUrl
        );
    }
    
    /**
    Синхронизировать файлы с CDN (для push CDN)

---

