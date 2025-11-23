# 🔄 Автоматическое обновление версии кеша

## Как это работает

Система **автоматически** отслеживает изменения CSS и JS файлов и обновляет версию кеша.

### Принцип работы:

```php
// В config.php
function getAssetVersion() {
    $files = [
        'assets/css/style.css',
        'assets/js/app.js'
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
    
    return $latestTime;
}

define('ASSET_VERSION', getAssetVersion());
```

**Версия = timestamp последнего изменения файлов**

---

## Что это дает

✅ **Автоматическое обновление** - не нужно вручную менять версию  
✅ **Уникальная версия** - каждое изменение = новая версия  
✅ **Кеш браузера** - старые версии кешируются на 1 год  
✅ **Мгновенное обновление** - новые файлы загружаются сразу  

---

## Пример работы

### 1. Изменяем CSS:
```css
/* Добавляем новый стиль */
.new-button {
    background: purple;
}
```

### 2. Версия обновляется автоматически:
```
Было: style.css?v=1763896015
Стало: style.css?v=1763899339
```

### 3. Браузеры загружают новый файл:
```
GET /assets/css/style.css?v=1763899339
Status: 200 OK (новый файл)
```

---

## Проверка работы

### Текущая версия:
```bash
docker-compose exec web php -r 'require "config.php"; echo ASSET_VERSION;'
```

### Изменить файл и проверить:
```bash
# 1. Изменить CSS
echo "/* test */" >> src/assets/css/style.css

# 2. Подождать 1 секунду (для обновления mtime)
sleep 1

# 3. Проверить новую версию
docker-compose exec web php -r 'require "config.php"; echo ASSET_VERSION;'
```

### В браузере:
```
Открыть DevTools → Network → обновить страницу
Проверить URL: style.css?v=НОВАЯ_ВЕРСИЯ
```

---

## Где используется

### Шаблоны с header/footer:
```php
<!-- В templates/header.php -->
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VERSION ?>">

<!-- В templates/footer.php -->
<script src="/assets/js/app.js?v=<?= ASSET_VERSION ?>"></script>
```

### Standalone страницы:
```php
<!-- В views/login.view.php -->
<link rel="stylesheet" href="/assets/css/style.css?v=<?= ASSET_VERSION ?>">
<script src="/assets/js/app.js?v=<?= ASSET_VERSION ?>"></script>
```

---

## Добавление новых файлов

Если добавляете новые CSS/JS файлы для отслеживания:

```php
// В config.php, в функции getAssetVersion()
$files = [
    __DIR__ . '/assets/css/style.css',
    __DIR__ . '/assets/js/app.js',
    __DIR__ . '/assets/css/admin.css',  // Новый файл
    __DIR__ . '/assets/js/charts.js'    // Новый файл
];
```

---

## Производительность

### Кеширование версии:
```php
function getAssetVersion() {
    static $version = null;  // Кешируем в памяти
    
    if ($version !== null) {
        return $version;  // Возвращаем из кеша
    }
    
    // Вычисляем только 1 раз за запрос
    $version = calculateVersion();
    return $version;
}
```

**Overhead**: ~0.1ms на запрос (проверка 2 файлов)

---

## Troubleshooting

### Версия не обновляется?

**Причина**: Файл не изменился или права доступа

**Решение**:
```bash
# Проверить права
docker-compose exec web ls -la /var/www/html/assets/

# Принудительно обновить mtime
docker-compose exec web touch /var/www/html/assets/css/style.css
```

### Версия одинаковая для всех файлов?

**Это нормально!** Версия = максимальный timestamp из всех файлов.

Если изменили только CSS, версия обновится для CSS и JS одновременно.

---

## Альтернативные подходы

### 1. Git commit hash (для production):
```php
define('ASSET_VERSION', trim(shell_exec('git rev-parse --short HEAD')));
// Результат: style.css?v=a3f2c1b
```

### 2. Build number (для CI/CD):
```php
define('ASSET_VERSION', getenv('BUILD_NUMBER') ?: time());
// Результат: style.css?v=12345
```

### 3. Файловый hash (максимальная точность):
```php
$hash = md5_file(__DIR__ . '/assets/css/style.css');
define('ASSET_VERSION', substr($hash, 0, 8));
// Результат: style.css?v=a3f2c1b4
```

---

## Рекомендации

✅ **Development**: Используйте timestamp (текущий подход)  
✅ **Production**: Рассмотрите git hash или build number  
✅ **CDN**: Используйте полный hash файла  

---

## Полезные ссылки

- [Основная документация по кешированию](CACHING.md)
- [Производительность](PERFORMANCE.md)
- [MDN: HTTP Caching](https://developer.mozilla.org/en-US/docs/Web/HTTP/Caching)
