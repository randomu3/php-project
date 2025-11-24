<?php
/**
 * Fix PHPDoc duplicates and formatting issues
 * 
 * This script removes duplicate PHPDoc blocks and fixes indentation
 */

echo "=== Fixing PHPDoc Duplicates ===\n\n";

$dirs = [
    '/var/www/html/controllers',
    '/var/www/html/helpers',
    '/var/www/html/core',
];

function fixFile($file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Remove duplicate PHPDoc blocks (2 or more consecutive /** */ blocks)
    $content = preg_replace(
        '/(\s+\/\*\*[^}]*?\*\/\s*){2,}(\s*(?:public|private|protected)\s+(?:const|function|\$))/s',
        "\n    /**\n     * Description\n     *\n     * @var mixed\n     */\n$2",
        $content
    );
    
    // Fix indentation for constants (should be 4 spaces)
    $lines = explode("\n", $content);
    $fixed_lines = [];
    
    foreach ($lines as $line) {
        // Fix public const that starts at column 0
        if (preg_match('/^public const /', $line)) {
            $line = '    ' . $line;
        }
        // Fix public const with wrong indentation
        if (preg_match('/^\s{1,3}public const /', $line)) {
            $line = preg_replace('/^\s+/', '    ', $line);
        }
        $fixed_lines[] = $line;
    }
    
    $content = implode("\n", $fixed_lines);
    
    // Remove extra blank lines (more than 2 consecutive)
    $content = preg_replace('/\n{3,}/', "\n\n", $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "✓ Fixed: " . basename($file) . "\n";
        return true;
    }
    
    return false;
}

$fixed = 0;
$total = 0;

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        echo "⚠ Directory not found: $dir\n";
        continue;
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->getExtension() === 'php') {
            $total++;
            if (fixFile($file->getPathname())) {
                $fixed++;
            }
        }
    }
}

echo "\n✓ Processed $total files, fixed $fixed files\n";
