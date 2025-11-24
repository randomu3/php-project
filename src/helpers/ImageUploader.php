<?php

namespace AuraUI\Helpers;

/**
 *  Image Uploader
 *
 * @package AuraUI\Helpers
 */
class ImageUploader
{
    /**
     * UploadDir
     *
     * @var string
     */
    private string $uploadDir;

    /**
     * MaxSize
     *
     * @var int
     */
    private int $maxSize = 5242880;

    // 5MB
    /**
     * AllowedTypes
     *
     * @var array
     */
    private array $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

    /**
     * AllowedExtensions
     *
     * @var array
     */
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    /**
     *   construct
     */
    public function __construct()
    {
        // Установить абсолютный путь к директории загрузок
        $this->uploadDir = __DIR__ . '/../uploads/avatars/';

        // Создать директорию если не существует
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Создать .htaccess для защиты
        $htaccess = $this->uploadDir . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "# Allow image access\n<FilesMatch \"\\.(jpg|jpeg|png|gif|webp)$\">\n    Require all granted\n</FilesMatch>");
        }
    }

    /**
     * Upload Avatar
     *
     * @param array $file Uploaded file from $_FILES
     * @param int $userId User ID
     *
     * @return array Data array
     */
    public function uploadAvatar(array $file, int $userId): array
    {
        // Проверка ошибок загрузки
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Некорректный файл'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => $this->getUploadError($file['error'])];
        }

        // Проверка размера
        if ($file['size'] > $this->maxSize) {
            return ['success' => false, 'error' => 'Файл слишком большой (максимум 5MB)'];
        }

        // Проверка типа файла
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedTypes)) {
            return ['success' => false, 'error' => 'Недопустимый тип файла. Разрешены: JPG, PNG, GIF, WebP'];
        }

        // Проверка расширения
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            return ['success' => false, 'error' => 'Недопустимое расширение файла'];
        }

        // Генерировать уникальное имя
        $filename = 'avatar_' . $userId . '_' . time() . '.' . $extension;
        $filepath = $this->uploadDir . $filename;

        // Переместить файл
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => false, 'error' => 'Не удалось сохранить файл'];
        }

        // Изменить размер изображения
        $resized = $this->resizeImage($filepath, 300, 300);
        if (!$resized) {
            // Если не удалось изменить размер, оставляем оригинал
            error_log('Failed to resize image: ' . $filepath);
        }

        return [
            'success' => true,
            'filename' => $filename,
            'path' => '/uploads/avatars/' . $filename
        ];
    }

    /**
     * Delete Avatar
     *
     * @param  $filename Parameter
     */
    public function deleteAvatar($filename)
    {
        if (empty($filename)) {
            return false;
        }

        $filepath = $this->uploadDir . basename($filename);
        if (file_exists($filepath)) {
            return unlink($filepath);
        }

        return false;
    }

    /**
     * Resize Image
     *
     * @param string $filepath Parameter
     * @param int $maxWidth Parameter
     * @param int $maxHeight Parameter
     */
    private function resizeImage(string $filepath, int $maxWidth, int $maxHeight)
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        [$width, $height, $type] = getimagesize($filepath);

        // Если изображение уже меньше, не изменяем
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }

        // Вычислить новые размеры с сохранением пропорций
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        // Создать исходное изображение
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filepath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filepath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($filepath);
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($filepath);
                break;
            default:
                return false;
        }

        if (!$source) {
            return false;
        }

        // Создать новое изображение
        $destination = imagecreatetruecolor($newWidth, $newHeight);

        // Сохранить прозрачность для PNG и GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($destination, false);
            imagesavealpha($destination, true);
            $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
            imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Изменить размер
        imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Сохранить
        $result = false;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($destination, $filepath, 90);
                break;
            case IMAGETYPE_PNG:
                $result = imagepng($destination, $filepath, 9);
                break;
            case IMAGETYPE_GIF:
                $result = imagegif($destination, $filepath);
                break;
            case IMAGETYPE_WEBP:
                $result = imagewebp($destination, $filepath, 90);
                break;
        }

        // Освободить память
        imagedestroy($source);
        imagedestroy($destination);

        return $result;
    }

    /**
     * Get Upload Error
     *
     * @param int $code Error code
     *
     * @return string String value
     */
    private function getUploadError(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Файл слишком большой',
            UPLOAD_ERR_PARTIAL => 'Файл загружен частично',
            UPLOAD_ERR_NO_FILE => 'Файл не был загружен',
            UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка',
            UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск',
            UPLOAD_ERR_EXTENSION => 'Загрузка файла остановлена расширением',
            default => 'Неизвестная ошибка загрузки',
        };
    }

    /**
     * Get Avatar Url
     *
     * @param  $avatar Parameter
     * @param  $username Username
     *
     * @return ?string
     */
    public static function getAvatarUrl($avatar, $username = ''): ?string
    {
        if (!empty($avatar) && file_exists('uploads/avatars/' . basename($avatar))) {
            return '/uploads/avatars/' . basename($avatar);
        }

        // Дефолтный аватар с первой буквой имени
        return null;
    }
}
