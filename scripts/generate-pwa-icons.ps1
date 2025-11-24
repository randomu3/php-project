# Generate PWA icons placeholder

Write-Host "Generating PWA icon placeholders..." -ForegroundColor Cyan

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$projectRoot = Split-Path -Parent $scriptDir
Push-Location $projectRoot

try {
    # Create images directory if not exists
    $imagesDir = "src/assets/images"
    if (!(Test-Path $imagesDir)) {
        New-Item -ItemType Directory -Path $imagesDir -Force | Out-Null
    }
    
    # Create PHP script to generate icons
    $phpScript = @'
<?php
// Generate placeholder PWA icons

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$dir = 'assets/images';

if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

foreach ($sizes as $size) {
    $img = imagecreatetruecolor($size, $size);
    
    // Gradient background
    for ($y = 0; $y < $size; $y++) {
        $r = 102 + ($y / $size) * (118 - 102);
        $g = 126 + ($y / $size) * (75 - 126);
        $b = 234 + ($y / $size) * (162 - 234);
        $color = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, 0, $y, $size, $y + 1, $color);
    }
    
    // Add text
    $white = imagecolorallocate($img, 255, 255, 255);
    $fontSize = $size / 8;
    $text = 'A';
    
    // Center text
    $bbox = imagettfbbox($fontSize, 0, __DIR__ . '/assets/fonts/arial.ttf', $text);
    if ($bbox) {
        $x = ($size - ($bbox[2] - $bbox[0])) / 2;
        $y = ($size - ($bbox[1] - $bbox[7])) / 2;
        imagettftext($img, $fontSize, 0, $x, $y, $white, __DIR__ . '/assets/fonts/arial.ttf', $text);
    } else {
        // Fallback without font
        imagestring($img, 5, $size/2 - 10, $size/2 - 10, 'A', $white);
    }
    
    // Save
    $filename = "{$dir}/icon-{$size}.png";
    imagepng($img, $filename);
    imagedestroy($img);
    
    echo "Created: {$filename}\n";
}

// Create badge
$badge = imagecreatetruecolor(72, 72);
$purple = imagecolorallocate($badge, 102, 126, 234);
imagefill($badge, 0, 0, $purple);
$white = imagecolorallocate($badge, 255, 255, 255);
imagestring($badge, 5, 26, 28, 'A', $white);
imagepng($badge, "{$dir}/badge-72.png");
imagedestroy($badge);

echo "Created: {$dir}/badge-72.png\n";
echo "\nAll icons generated!\n";
'@
    
    $phpScript | Out-File -Encoding UTF8 temp_icons.php
    
    # Run script
    docker-compose -f docker-compose.dev.yml exec -T web php temp_icons.php
    
    # Clean up
    Remove-Item temp_icons.php -ErrorAction SilentlyContinue
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "SUCCESS: PWA icons generated!" -ForegroundColor Green
    } else {
        Write-Host "ERROR: Failed to generate icons" -ForegroundColor Red
    }
    
} finally {
    Pop-Location
}
