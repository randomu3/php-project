# Minifier

**Файл**: `/var/www/html/helpers/Minifier.php`

**Категория**: Helpers

## Описание

Minifier - минификация CSS, JS и HTML

## Методы

### `css($css)`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS

---

### `js($js)`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS
    /
    public static function css($css) {
        // Удаляем комментарии
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем пробелы
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг символов
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Удаляем последнюю точку с запятой
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
    Минифицировать JavaScript

---

### `html($html)`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS
    /
    public static function css($css) {
        // Удаляем комментарии
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем пробелы
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг символов
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Удаляем последнюю точку с запятой
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
    Минифицировать JavaScript
    /
    public static function js($js) {
        // Удаляем однострочные комментарии
        $js = preg_replace('~//[^\n]*~', '', $js);
        
        // Удаляем многострочные комментарии
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Удаляем переносы строк и лишние пробелы
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Удаляем пробелы вокруг операторов
        $js = preg_replace('/\s*([{}();,:])\s*/', '$1', $js);
        
        return trim($js);
    }
    
    /**
    Минифицировать HTML

---

### `cssFile($inputFile, $outputFile = null)`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS
    /
    public static function css($css) {
        // Удаляем комментарии
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем пробелы
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг символов
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Удаляем последнюю точку с запятой
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
    Минифицировать JavaScript
    /
    public static function js($js) {
        // Удаляем однострочные комментарии
        $js = preg_replace('~//[^\n]*~', '', $js);
        
        // Удаляем многострочные комментарии
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Удаляем переносы строк и лишние пробелы
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Удаляем пробелы вокруг операторов
        $js = preg_replace('/\s*([{}();,:])\s*/', '$1', $js);
        
        return trim($js);
    }
    
    /**
    Минифицировать HTML
    /
    public static function html($html) {
        // Сохраняем содержимое <pre>, <textarea>, <script>
        $preserve = [];
        $html = preg_replace_callback(
            '/<(pre|textarea|script)[^>]*>.*?<\/\1>/is',
            function($matches) use (&$preserve) {
                $key = '___PRESERVE_' . count($preserve) . '___';
                $preserve[$key] = $matches[0];
                return $key;
            },
            $html
        );
        
        // Удаляем HTML комментарии (кроме условных)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        
        // Удаляем пробелы между тегами
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Удаляем переносы строк и лишние пробелы
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Восстанавливаем сохраненное содержимое
        foreach ($preserve as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        
        return trim($html);
    }
    
    /**
    Минифицировать файл CSS

---

### `jsFile($inputFile, $outputFile = null)`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS
    /
    public static function css($css) {
        // Удаляем комментарии
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем пробелы
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг символов
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Удаляем последнюю точку с запятой
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
    Минифицировать JavaScript
    /
    public static function js($js) {
        // Удаляем однострочные комментарии
        $js = preg_replace('~//[^\n]*~', '', $js);
        
        // Удаляем многострочные комментарии
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Удаляем переносы строк и лишние пробелы
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Удаляем пробелы вокруг операторов
        $js = preg_replace('/\s*([{}();,:])\s*/', '$1', $js);
        
        return trim($js);
    }
    
    /**
    Минифицировать HTML
    /
    public static function html($html) {
        // Сохраняем содержимое <pre>, <textarea>, <script>
        $preserve = [];
        $html = preg_replace_callback(
            '/<(pre|textarea|script)[^>]*>.*?<\/\1>/is',
            function($matches) use (&$preserve) {
                $key = '___PRESERVE_' . count($preserve) . '___';
                $preserve[$key] = $matches[0];
                return $key;
            },
            $html
        );
        
        // Удаляем HTML комментарии (кроме условных)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        
        // Удаляем пробелы между тегами
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Удаляем переносы строк и лишние пробелы
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Восстанавливаем сохраненное содержимое
        foreach ($preserve as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        
        return trim($html);
    }
    
    /**
    Минифицировать файл CSS
    /
    public static function cssFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            Logger::error('CSS file not found', ['file' => $inputFile]);
            return false;
        }
        
        $css = file_get_contents($inputFile);
        $minified = self::css($css);
        
        $outputFile = $outputFile ?? str_replace('.css', '.min.css', $inputFile);
        
        $result = file_put_contents($outputFile, $minified);
        
        if ($result) {
            $savings = round((1 - strlen($minified) / strlen($css))100, 2);
            Logger::info('CSS minified', [
                'input' => $inputFile,
                'output' => $outputFile,
                'original_size' => strlen($css),
                'minified_size' => strlen($minified),
                'savings' => $savings . '%'
            ]);
        }
        
        return $result !== false;
    }
    
    /**
    Минифицировать файл JS

---

### `minifyDirectory($directory)`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS
    /
    public static function css($css) {
        // Удаляем комментарии
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем пробелы
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг символов
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Удаляем последнюю точку с запятой
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
    Минифицировать JavaScript
    /
    public static function js($js) {
        // Удаляем однострочные комментарии
        $js = preg_replace('~//[^\n]*~', '', $js);
        
        // Удаляем многострочные комментарии
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Удаляем переносы строк и лишние пробелы
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Удаляем пробелы вокруг операторов
        $js = preg_replace('/\s*([{}();,:])\s*/', '$1', $js);
        
        return trim($js);
    }
    
    /**
    Минифицировать HTML
    /
    public static function html($html) {
        // Сохраняем содержимое <pre>, <textarea>, <script>
        $preserve = [];
        $html = preg_replace_callback(
            '/<(pre|textarea|script)[^>]*>.*?<\/\1>/is',
            function($matches) use (&$preserve) {
                $key = '___PRESERVE_' . count($preserve) . '___';
                $preserve[$key] = $matches[0];
                return $key;
            },
            $html
        );
        
        // Удаляем HTML комментарии (кроме условных)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        
        // Удаляем пробелы между тегами
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Удаляем переносы строк и лишние пробелы
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Восстанавливаем сохраненное содержимое
        foreach ($preserve as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        
        return trim($html);
    }
    
    /**
    Минифицировать файл CSS
    /
    public static function cssFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            Logger::error('CSS file not found', ['file' => $inputFile]);
            return false;
        }
        
        $css = file_get_contents($inputFile);
        $minified = self::css($css);
        
        $outputFile = $outputFile ?? str_replace('.css', '.min.css', $inputFile);
        
        $result = file_put_contents($outputFile, $minified);
        
        if ($result) {
            $savings = round((1 - strlen($minified) / strlen($css))100, 2);
            Logger::info('CSS minified', [
                'input' => $inputFile,
                'output' => $outputFile,
                'original_size' => strlen($css),
                'minified_size' => strlen($minified),
                'savings' => $savings . '%'
            ]);
        }
        
        return $result !== false;
    }
    
    /**
    Минифицировать файл JS
    /
    public static function jsFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            Logger::error('JS file not found', ['file' => $inputFile]);
            return false;
        }
        
        $js = file_get_contents($inputFile);
        $minified = self::js($js);
        
        $outputFile = $outputFile ?? str_replace('.js', '.min.js', $inputFile);
        
        $result = file_put_contents($outputFile, $minified);
        
        if ($result) {
            $savings = round((1 - strlen($minified) / strlen($js))100, 2);
            Logger::info('JS minified', [
                'input' => $inputFile,
                'output' => $outputFile,
                'original_size' => strlen($js),
                'minified_size' => strlen($minified),
                'savings' => $savings . '%'
            ]);
        }
        
        return $result !== false;
    }
    
    /**
    Минифицировать все CSS/JS в директории

---

### `enableHTMLMinification()`

Minifier - минификация CSS, JS и HTML
/
class Minifier {
    
    /**
    Минифицировать CSS
    /
    public static function css($css) {
        // Удаляем комментарии
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Удаляем пробелы
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Удаляем пробелы вокруг символов
        $css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
        
        // Удаляем последнюю точку с запятой
        $css = preg_replace('/;}/', '}', $css);
        
        return trim($css);
    }
    
    /**
    Минифицировать JavaScript
    /
    public static function js($js) {
        // Удаляем однострочные комментарии
        $js = preg_replace('~//[^\n]*~', '', $js);
        
        // Удаляем многострочные комментарии
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Удаляем переносы строк и лишние пробелы
        $js = preg_replace('/\s+/', ' ', $js);
        
        // Удаляем пробелы вокруг операторов
        $js = preg_replace('/\s*([{}();,:])\s*/', '$1', $js);
        
        return trim($js);
    }
    
    /**
    Минифицировать HTML
    /
    public static function html($html) {
        // Сохраняем содержимое <pre>, <textarea>, <script>
        $preserve = [];
        $html = preg_replace_callback(
            '/<(pre|textarea|script)[^>]*>.*?<\/\1>/is',
            function($matches) use (&$preserve) {
                $key = '___PRESERVE_' . count($preserve) . '___';
                $preserve[$key] = $matches[0];
                return $key;
            },
            $html
        );
        
        // Удаляем HTML комментарии (кроме условных)
        $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
        
        // Удаляем пробелы между тегами
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Удаляем переносы строк и лишние пробелы
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Восстанавливаем сохраненное содержимое
        foreach ($preserve as $key => $value) {
            $html = str_replace($key, $value, $html);
        }
        
        return trim($html);
    }
    
    /**
    Минифицировать файл CSS
    /
    public static function cssFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            Logger::error('CSS file not found', ['file' => $inputFile]);
            return false;
        }
        
        $css = file_get_contents($inputFile);
        $minified = self::css($css);
        
        $outputFile = $outputFile ?? str_replace('.css', '.min.css', $inputFile);
        
        $result = file_put_contents($outputFile, $minified);
        
        if ($result) {
            $savings = round((1 - strlen($minified) / strlen($css))100, 2);
            Logger::info('CSS minified', [
                'input' => $inputFile,
                'output' => $outputFile,
                'original_size' => strlen($css),
                'minified_size' => strlen($minified),
                'savings' => $savings . '%'
            ]);
        }
        
        return $result !== false;
    }
    
    /**
    Минифицировать файл JS
    /
    public static function jsFile($inputFile, $outputFile = null) {
        if (!file_exists($inputFile)) {
            Logger::error('JS file not found', ['file' => $inputFile]);
            return false;
        }
        
        $js = file_get_contents($inputFile);
        $minified = self::js($js);
        
        $outputFile = $outputFile ?? str_replace('.js', '.min.js', $inputFile);
        
        $result = file_put_contents($outputFile, $minified);
        
        if ($result) {
            $savings = round((1 - strlen($minified) / strlen($js))100, 2);
            Logger::info('JS minified', [
                'input' => $inputFile,
                'output' => $outputFile,
                'original_size' => strlen($js),
                'minified_size' => strlen($minified),
                'savings' => $savings . '%'
            ]);
        }
        
        return $result !== false;
    }
    
    /**
    Минифицировать все CSS/JS в директории
    /
    public static function minifyDirectory($directory) {
        if (!is_dir($directory)) {
            return false;
        }
        
        $cssCount = 0;
        $jsCount = 0;
        
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );
        
        foreach ($files as $file) {
            if ($file->isFile()) {
                $path = $file->getPathname();
                $ext = strtolower($file->getExtension());
                
                // Пропускаем уже минифицированные файлы
                if (strpos($path, '.min.') !== false) {
                    continue;
                }
                
                if ($ext === 'css') {
                    self::cssFile($path);
                    $cssCount++;
                } elseif ($ext === 'js') {
                    self::jsFile($path);
                    $jsCount++;
                }
            }
        }
        
        Logger::info('Directory minification completed', [
            'directory' => $directory,
            'css_files' => $cssCount,
            'js_files' => $jsCount
        ]);
        
        return ['css' => $cssCount, 'js' => $jsCount];
    }
    
    /**
    Включить минификацию HTML на лету

---

