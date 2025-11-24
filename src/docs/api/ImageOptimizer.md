# ImageOptimizer

**Файл**: `/var/www/html/helpers/ImageOptimizer.php`

**Категория**: Helpers

## Описание

ImageOptimizer - конвертация и оптимизация изображений

## Методы

### `convertToWebP($sourcePath, $quality = 80)`

ImageOptimizer - конвертация и оптимизация изображений
/
class ImageOptimizer {
    
    /**
    Конвертировать изображение в WebP

---

### `getPictureTag($imagePath, $alt = '', $class = '', $lazy = true)`

ImageOptimizer - конвертация и оптимизация изображений
/
class ImageOptimizer {
    
    /**
    Конвертировать изображение в WebP
    /
    public static function convertToWebP($sourcePath, $quality = 80) {
        if (!file_exists($sourcePath)) {
            Logger::error('Image not found', ['path' => $sourcePath]);
            return false;
        }
        
        // Проверяем поддержку WebP
        if (!function_exists('imagewebp')) {
            Logger::warning('WebP not supported');
            return false;
        }
        
        $pathInfo = pathinfo($sourcePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Если WebP уже существует и новее оригинала
        if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($sourcePath)) {
            return $webpPath;
        }
        
        try {
            // Определяем тип изображения
            $imageType = exif_imagetype($sourcePath);
            
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($sourcePath);
                    // Сохраняем прозрачность
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($sourcePath);
                    break;
                default:
                    Logger::error('Unsupported image type', ['type' => $imageType]);
                    return false;
            }
            
            if (!$image) {
                Logger::error('Failed to create image', ['path' => $sourcePath]);
                return false;
            }
            
            // Конвертируем в WebP
            $result = imagewebp($image, $webpPath, $quality);
            imagedestroy($image);
            
            if ($result) {
                Logger::info('Image converted to WebP', [
                    'source' => $sourcePath,
                    'webp' => $webpPath,
                    'original_size' => filesize($sourcePath),
                    'webp_size' => filesize($webpPath),
                    'savings' => round((1 - filesize($webpPath) / filesize($sourcePath))100, 2) . '%'
                ]);
                return $webpPath;
            }
            
            return false;
            
        } catch (Exception $e) {
            Logger::error('WebP conversion error', [
                'path' => $sourcePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Получить HTML для picture с WebP и fallback

---

### `resize($sourcePath, $maxWidth, $maxHeight, $quality = 85)`

ImageOptimizer - конвертация и оптимизация изображений
/
class ImageOptimizer {
    
    /**
    Конвертировать изображение в WebP
    /
    public static function convertToWebP($sourcePath, $quality = 80) {
        if (!file_exists($sourcePath)) {
            Logger::error('Image not found', ['path' => $sourcePath]);
            return false;
        }
        
        // Проверяем поддержку WebP
        if (!function_exists('imagewebp')) {
            Logger::warning('WebP not supported');
            return false;
        }
        
        $pathInfo = pathinfo($sourcePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Если WebP уже существует и новее оригинала
        if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($sourcePath)) {
            return $webpPath;
        }
        
        try {
            // Определяем тип изображения
            $imageType = exif_imagetype($sourcePath);
            
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($sourcePath);
                    // Сохраняем прозрачность
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($sourcePath);
                    break;
                default:
                    Logger::error('Unsupported image type', ['type' => $imageType]);
                    return false;
            }
            
            if (!$image) {
                Logger::error('Failed to create image', ['path' => $sourcePath]);
                return false;
            }
            
            // Конвертируем в WebP
            $result = imagewebp($image, $webpPath, $quality);
            imagedestroy($image);
            
            if ($result) {
                Logger::info('Image converted to WebP', [
                    'source' => $sourcePath,
                    'webp' => $webpPath,
                    'original_size' => filesize($sourcePath),
                    'webp_size' => filesize($webpPath),
                    'savings' => round((1 - filesize($webpPath) / filesize($sourcePath))100, 2) . '%'
                ]);
                return $webpPath;
            }
            
            return false;
            
        } catch (Exception $e) {
            Logger::error('WebP conversion error', [
                'path' => $sourcePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Получить HTML для picture с WebP и fallback
    /
    public static function getPictureTag($imagePath, $alt = '', $class = '', $lazy = true) {
        $pathInfo = pathinfo($imagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Конвертируем если WebP не существует
        if (!file_exists($webpPath)) {
            self::convertToWebP($imagePath);
        }
        
        $lazyAttr = $lazy ? 'loading="lazy"' : '';
        $dataSrc = $lazy ? 'data-src' : 'src';
        
        $html = '<picture>';
        
        // WebP source
        if (file_exists($webpPath)) {
            $html .= sprintf(
                '<source %s="%s" type="image/webp">',
                $dataSrc,
                htmlspecialchars($webpPath)
            );
        }
        
        // Fallback
        $html .= sprintf(
            '<img %s="%s" alt="%s" class="%s" %s>',
            $dataSrc,
            htmlspecialchars($imagePath),
            htmlspecialchars($alt),
            htmlspecialchars($class),
            $lazyAttr
        );
        
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
    Оптимизировать изображение (изменить размер)

---

### `convertDirectory($directory, $quality = 80)`

ImageOptimizer - конвертация и оптимизация изображений
/
class ImageOptimizer {
    
    /**
    Конвертировать изображение в WebP
    /
    public static function convertToWebP($sourcePath, $quality = 80) {
        if (!file_exists($sourcePath)) {
            Logger::error('Image not found', ['path' => $sourcePath]);
            return false;
        }
        
        // Проверяем поддержку WebP
        if (!function_exists('imagewebp')) {
            Logger::warning('WebP not supported');
            return false;
        }
        
        $pathInfo = pathinfo($sourcePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Если WebP уже существует и новее оригинала
        if (file_exists($webpPath) && filemtime($webpPath) >= filemtime($sourcePath)) {
            return $webpPath;
        }
        
        try {
            // Определяем тип изображения
            $imageType = exif_imagetype($sourcePath);
            
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($sourcePath);
                    // Сохраняем прозрачность
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($sourcePath);
                    break;
                default:
                    Logger::error('Unsupported image type', ['type' => $imageType]);
                    return false;
            }
            
            if (!$image) {
                Logger::error('Failed to create image', ['path' => $sourcePath]);
                return false;
            }
            
            // Конвертируем в WebP
            $result = imagewebp($image, $webpPath, $quality);
            imagedestroy($image);
            
            if ($result) {
                Logger::info('Image converted to WebP', [
                    'source' => $sourcePath,
                    'webp' => $webpPath,
                    'original_size' => filesize($sourcePath),
                    'webp_size' => filesize($webpPath),
                    'savings' => round((1 - filesize($webpPath) / filesize($sourcePath))100, 2) . '%'
                ]);
                return $webpPath;
            }
            
            return false;
            
        } catch (Exception $e) {
            Logger::error('WebP conversion error', [
                'path' => $sourcePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Получить HTML для picture с WebP и fallback
    /
    public static function getPictureTag($imagePath, $alt = '', $class = '', $lazy = true) {
        $pathInfo = pathinfo($imagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Конвертируем если WebP не существует
        if (!file_exists($webpPath)) {
            self::convertToWebP($imagePath);
        }
        
        $lazyAttr = $lazy ? 'loading="lazy"' : '';
        $dataSrc = $lazy ? 'data-src' : 'src';
        
        $html = '<picture>';
        
        // WebP source
        if (file_exists($webpPath)) {
            $html .= sprintf(
                '<source %s="%s" type="image/webp">',
                $dataSrc,
                htmlspecialchars($webpPath)
            );
        }
        
        // Fallback
        $html .= sprintf(
            '<img %s="%s" alt="%s" class="%s" %s>',
            $dataSrc,
            htmlspecialchars($imagePath),
            htmlspecialchars($alt),
            htmlspecialchars($class),
            $lazyAttr
        );
        
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
    Оптимизировать изображение (изменить размер)
    /
    public static function resize($sourcePath, $maxWidth, $maxHeight, $quality = 85) {
        if (!file_exists($sourcePath)) {
            return false;
        }
        
        try {
            list($width, $height, $type) = getimagesize($sourcePath);
            
            // Если изображение меньше максимального размера - не изменяем
            if ($width <= $maxWidth && $height <= $maxHeight) {
                return $sourcePath;
            }
            
            // Вычисляем новые размеры с сохранением пропорций
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = round($width$ratio);
            $newHeight = round($height$ratio);
            
            // Создаем новое изображение
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Загружаем оригинал
            switch ($type) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($sourcePath);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($sourcePath);
                    // Сохраняем прозрачность
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                    break;
                case IMAGETYPE_GIF:
                    $source = imagecreatefromgif($sourcePath);
                    break;
                default:
                    return false;
            }
            
            // Изменяем размер
            imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            
            // Сохраняем
            $pathInfo = pathinfo($sourcePath);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_' . $maxWidth . 'x' . $maxHeight . '.' . $pathInfo['extension'];
            
            switch ($type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($newImage, $resizedPath, $quality);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($newImage, $resizedPath, 9);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($newImage, $resizedPath);
                    break;
            }
            
            imagedestroy($source);
            imagedestroy($newImage);
            
            Logger::info('Image resized', [
                'source' => $sourcePath,
                'resized' => $resizedPath,
                'original_size' => "{$width}x{$height}",
                'new_size' => "{$newWidth}x{$newHeight}"
            ]);
            
            return $resizedPath;
            
        } catch (Exception $e) {
            Logger::error('Image resize error', [
                'path' => $sourcePath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
    Пакетная конвертация всех изображений в директории

---

