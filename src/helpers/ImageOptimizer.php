<?php

namespace AuraUI\Helpers;

use Exception;

/**
 *  Image Optimizer
 *
 * @package AuraUI\Helpers
 */
class ImageOptimizer
{
    /**
     * Convert To Web P
     *
     * @param mixed $sourcePath Parameter
     * @param mixed $quality Parameter
     *
     * @return false|string
     */
    public static function convertToWebP(mixed $sourcePath, mixed $quality = 80): false|string
    {
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
                    'savings' => round((1 - filesize($webpPath) / filesize($sourcePath)) * 100, 2) . '%'
                ]);
                return $webpPath;
            }

            return false;

        } catch (Exception $exception) {
            Logger::error('WebP conversion error', [
                'path' => $sourcePath,
                'error' => $exception->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get Picture Tag
     *
     * @param mixed $imagePath Parameter
     * @param mixed $alt Parameter
     * @param mixed $class Parameter
     * @param mixed $lazy Parameter
     *
     * @return string String value
     */
    public static function getPictureTag(mixed $imagePath, mixed $alt = '', mixed $class = '', mixed $lazy = true): string
    {
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

        return $html . '</picture>';
    }

    /**
     * Resize
     *
     * @param mixed $sourcePath Parameter
     * @param mixed $maxWidth Parameter
     * @param mixed $maxHeight Parameter
     * @param mixed $quality Parameter
     */
    public static function resize(mixed $sourcePath, mixed $maxWidth, mixed $maxHeight, mixed $quality = 85)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }

        try {
            [$width, $height, $type] = getimagesize($sourcePath);

            // Если изображение меньше максимального размера - не изменяем
            if ($width <= $maxWidth && $height <= $maxHeight) {
                return $sourcePath;
            }

            // Вычисляем новые размеры с сохранением пропорций
            $ratio = min($maxWidth / $width, $maxHeight / $height);
            $newWidth = round($width * $ratio);
            $newHeight = round($height * $ratio);

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
                'original_size' => sprintf('%dx%d', $width, $height),
                'new_size' => sprintf('%sx%s', $newWidth, $newHeight)
            ]);

            return $resizedPath;

        } catch (Exception $exception) {
            Logger::error('Image resize error', [
                'path' => $sourcePath,
                'error' => $exception->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Convert Directory
     *
     * @param mixed $directory Parameter
     * @param mixed $quality Parameter
     *
     * @return false|array
     */
    public static function convertDirectory(mixed $directory, mixed $quality = 80): false|array
    {
        if (!is_dir($directory)) {
            return false;
        }

        $extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $converted = 0;
        $errors = 0;

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $ext = strtolower($file->getExtension());
                if (in_array($ext, $extensions)) {
                    $result = self::convertToWebP($file->getPathname(), $quality);
                    if ($result) {
                        $converted++;
                    } else {
                        $errors++;
                    }
                }
            }
        }

        Logger::info('Batch WebP conversion completed', [
            'directory' => $directory,
            'converted' => $converted,
            'errors' => $errors
        ]);

        return ['converted' => $converted, 'errors' => $errors];
    }
}
