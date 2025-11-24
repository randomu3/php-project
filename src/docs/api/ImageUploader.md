# ImageUploader

**Файл**: `/var/www/html/helpers/ImageUploader.php`

**Категория**: Helpers

## Описание

Загрузить аватар

## Методы

### `__construct()`

---

### `uploadAvatar($file, $userId)`

Загрузить аватар

---

### `deleteAvatar($filename)`

Загрузить аватар
    /
    public function uploadAvatar($file, $userId) {
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
            error_log("Failed to resize image: $filepath");
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'path' => '/' . $filepath
        ];
    }
    
    /**
    Удалить аватар

---

### `resizeImage($filepath, $maxWidth, $maxHeight)`

Загрузить аватар
    /
    public function uploadAvatar($file, $userId) {
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
            error_log("Failed to resize image: $filepath");
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'path' => '/' . $filepath
        ];
    }
    
    /**
    Удалить аватар
    /
    public function deleteAvatar($filename) {
        if (empty($filename)) return false;
        
        $filepath = $this->uploadDir . basename($filename);
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
    
    /**
    Изменить размер изображения

---

### `getUploadError($code)`

Загрузить аватар
    /
    public function uploadAvatar($file, $userId) {
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
            error_log("Failed to resize image: $filepath");
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'path' => '/' . $filepath
        ];
    }
    
    /**
    Удалить аватар
    /
    public function deleteAvatar($filename) {
        if (empty($filename)) return false;
        
        $filepath = $this->uploadDir . basename($filename);
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
    
    /**
    Изменить размер изображения
    /
    private function resizeImage($filepath, $maxWidth, $maxHeight) {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        list($width, $height, $type) = getimagesize($filepath);
        
        // Если изображение уже меньше, не изменяем
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }
        
        // Вычислить новые размеры с сохранением пропорций
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width$ratio);
        $newHeight = round($height$ratio);
        
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
        
        if (!$source) return false;
        
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
    Получить текст ошибки загрузки

---

### `getAvatarUrl($avatar, $username = '')`

Загрузить аватар
    /
    public function uploadAvatar($file, $userId) {
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
            error_log("Failed to resize image: $filepath");
        }
        
        return [
            'success' => true,
            'filename' => $filename,
            'path' => '/' . $filepath
        ];
    }
    
    /**
    Удалить аватар
    /
    public function deleteAvatar($filename) {
        if (empty($filename)) return false;
        
        $filepath = $this->uploadDir . basename($filename);
        if (file_exists($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }
    
    /**
    Изменить размер изображения
    /
    private function resizeImage($filepath, $maxWidth, $maxHeight) {
        if (!extension_loaded('gd')) {
            return false;
        }
        
        list($width, $height, $type) = getimagesize($filepath);
        
        // Если изображение уже меньше, не изменяем
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return true;
        }
        
        // Вычислить новые размеры с сохранением пропорций
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width$ratio);
        $newHeight = round($height$ratio);
        
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
        
        if (!$source) return false;
        
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
    Получить текст ошибки загрузки
    /
    private function getUploadError($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Файл слишком большой';
            case UPLOAD_ERR_PARTIAL:
                return 'Файл загружен частично';
            case UPLOAD_ERR_NO_FILE:
                return 'Файл не был загружен';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Отсутствует временная папка';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Не удалось записать файл на диск';
            case UPLOAD_ERR_EXTENSION:
                return 'Загрузка файла остановлена расширением';
            default:
                return 'Неизвестная ошибка загрузки';
        }
    }
    
    /**
    Получить URL аватара или дефолтный

---

