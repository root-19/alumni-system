<?php

/**
 * Quick Symlink Check Script
 * Run: php check-symlink.php
 */

$symlinkPath = __DIR__ . '/public/storage';
$targetPath = __DIR__ . '/storage/app/public';

echo "========================================\n";
echo "Symlink Verification\n";
echo "========================================\n\n";

echo "Symlink Path: {$symlinkPath}\n";
echo "Target Path: {$targetPath}\n\n";

if (is_link($symlinkPath)) {
    $linkTarget = readlink($symlinkPath);
    echo "✅ Symlink EXISTS\n";
    echo "Link Target: {$linkTarget}\n";
    
    $resolvedPath = realpath($symlinkPath);
    if ($resolvedPath) {
        echo "Resolved Path: {$resolvedPath}\n";
        echo "✅ Symlink is VALID\n";
    } else {
        echo "❌ Symlink is BROKEN (target doesn't exist)\n";
    }
} else {
    echo "❌ Symlink DOES NOT EXIST\n";
    echo "\nTo create it, run:\n";
    echo "php artisan storage:link\n";
}

echo "\n";

// Check if target directory exists
if (is_dir($targetPath)) {
    echo "✅ Target directory EXISTS: {$targetPath}\n";
    
    // Check if image files exist
    $alumniPostsPath = $targetPath . '/alumni-posts';
    if (is_dir($alumniPostsPath)) {
        $files = glob($alumniPostsPath . '/*.png');
        echo "✅ Alumni posts directory exists\n";
        echo "Image files found: " . count($files) . "\n";
        if (count($files) > 0) {
            echo "Sample files:\n";
            foreach (array_slice($files, 0, 3) as $file) {
                echo "  - " . basename($file) . "\n";
            }
        }
    } else {
        echo "⚠️  Alumni posts directory does not exist\n";
    }
} else {
    echo "❌ Target directory DOES NOT EXIST: {$targetPath}\n";
}

echo "\n========================================\n";

