<?php
/**
 * Add complete PHPDoc to all methods
 * 
 * This script adds @param and @return tags to methods that are missing them
 */

echo "=== Adding Missing PHPDoc ===\n\n";

$dirs = [
    '/var/www/html/controllers',
    '/var/www/html/helpers',
    '/var/www/html/core',
];

function addPhpDoc($file) {
    $content = file_get_contents($file);
    $original = $content;
    
    // Find methods without @return tag
    $pattern = '/(\/\*\*.*?\*\/)\s*(public|private|protected)\s+function\s+(\w+)\s*\([^)]*\)\s*:\s*(\w+)/s';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $phpdoc = $matches[1];
        $visibility = $matches[2];
        $methodName = $matches[3];
        $returnType = $matches[4];
        
        // Check if @return already exists
        if (strpos($phpdoc, '@return') !== false) {
            return $matches[0]; // Already has @return
        }
        
        // Add @return before closing */
        $returnDesc = match($returnType) {
            'void' => '',
            'bool' => 'True on success, false on failure',
            'array' => 'Array of data',
            'int' => 'Integer value',
            'string' => 'String value',
            'self' => 'Current instance',
            default => 'Return value'
        };
        
        $newPhpDoc = str_replace(
            ' */',
            " *\n     * @return $returnType" . ($returnDesc ? " $returnDesc" : '') . "\n     */",
            $phpdoc
        );
        
        return $newPhpDoc . "\n    " . $visibility . ' function ' . $methodName . $matches[0];
    }, $content);
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "✓ Updated: " . basename($file) . "\n";
        return true;
    }
    
    return false;
}

$updated = 0;
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
            if (addPhpDoc($file->getPathname())) {
                $updated++;
            }
        }
    }
}

echo "\n✓ Processed $total files, updated $updated files\n";
