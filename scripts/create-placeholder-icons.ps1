# Create placeholder icons for PWA
$sizes = @(72, 96, 128, 144, 152, 192, 384, 512)
$outputDir = "src/assets/images"

if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Force -Path $outputDir | Out-Null
}

$hasImageMagick = Get-Command "magick" -ErrorAction SilentlyContinue

if ($hasImageMagick) {
    Write-Host "Creating icons with ImageMagick..." -ForegroundColor Green
    
    foreach ($size in $sizes) {
        $output = "$outputDir/icon-$size.png"
        & magick -size "$size`x$size" gradient:"#9333ea-#db2777" -gravity center -font Arial-Bold -pointsize ($size * 0.6) -fill white -annotate +0+0 "Z" $output
        Write-Host "Created: icon-$size.png"
    }
} else {
    Write-Host "Creating placeholder icons..." -ForegroundColor Yellow
    
    $pngBase64 = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=="
    $pngBytes = [Convert]::FromBase64String($pngBase64)
    
    foreach ($size in $sizes) {
        $output = "$outputDir/icon-$size.png"
        [System.IO.File]::WriteAllBytes($output, $pngBytes)
        Write-Host "Created: icon-$size.png"
    }
}

Write-Host "Done! Icons created in $outputDir" -ForegroundColor Green
