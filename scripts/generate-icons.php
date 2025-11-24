<?php
/**
 * Генератор PWA иконок
 * Создает простые иконки с градиентом и молнией
 */

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$outputDir = __DIR__ . '/../src/assets/images/';

// Убедимся что директория существует
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

foreach ($sizes as $size) {
    // Создаем изображение
    $img = imagecreatetruecolor($size, $size);
    
    // Включаем альфа-канал
    imagesavealpha($img, true);
    
    // Создаем градиент от фиолетового к розовому
    for ($y = 0; $y < $size; $y++) {
        $ratio = $y / $size;
        
        // От #9333ea (фиолетовый) к #db2777 (розовый)
        $r = (int)(147 + ($ratio * (219 - 147)));
        $g = (int)(51 + ($ratio * (39 - 51)));
        $b = (int)(234 + ($ratio * (119 - 234)));
        
        $color = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, 0, $y, $size, $y + 1, $color);
    }
    
    // Добавляем белый цвет для молнии
    $white = imagecolorallocate($img, 255, 255, 255);
    
    // Рисуем простую молнию (упрощенная версия)
    $centerX = $size / 2;
    $centerY = $size / 2;
    $scale = $size / 100;
    
    // Координаты молнии (масштабируемые)
    $points = [
        $centerX, $centerY - 25 * $scale,  // Верх
        $centerX - 8 * $scale, $centerY,   // Левая середина
        $centerX + 5 * $scale, $centerY,   // Правая середина
        $centerX - 5 * $scale, $centerY + 25 * $scale, // Низ
        $centerX + 8 * $scale, $centerY,   // Правая середина 2
        $centerX - 5 * $scale, $centerY,   // Левая середина 2
    ];
    
    imagefilledpolygon($img, $points, 6, $white);
    
    // Сохраняем PNG
    $filename = $outputDir . "icon-{$size}.png";
    imagepng($img, $filename, 9);
    imagedestroy($img);
    
    echo "Создана иконка: icon-{$size}.png\n";
}

echo "\nВсе иконки успешно созданы!\n";
