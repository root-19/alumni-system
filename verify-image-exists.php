<?php

/**
 * Verify Image Exists Script
 * Run: php verify-image-exists.php
 * 
 * This script checks if an image file exists in storage and if the symlink is working
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$imagePath = 'alumni-posts/z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg';

echo "========================================\n";
echo "Image Verification\n";
echo "========================================\n\n";

echo "Checking image path: {$imagePath}\n\n";

// Check if file exists in storage/app/public
$storagePath = storage_path('app/public/' . $imagePath);
echo "Storage path: {$storagePath}\n";
if (file_exists($storagePath)) {
    echo "✅ File EXISTS in storage/app/public/\n";
    echo "   File size: " . filesize($storagePath) . " bytes\n";
    echo "   Readable: " . (is_readable($storagePath) ? 'Yes' : 'No') . "\n";
} else {
    echo "❌ File DOES NOT EXIST in storage/app/public/\n";
}

echo "\n";

// Check if symlink exists
$symlinkPath = public_path('storage');
echo "Symlink path: {$symlinkPath}\n";
if (is_link($symlinkPath)) {
    $linkTarget = readlink($symlinkPath);
    echo "✅ Symlink EXISTS\n";
    echo "   Link target: {$linkTarget}\n";
    
    $resolvedPath = realpath($symlinkPath);
    if ($resolvedPath) {
        echo "   Resolved path: {$resolvedPath}\n";
        echo "✅ Symlink is VALID\n";
    } else {
        echo "❌ Symlink is BROKEN\n";
    }
} elseif (is_dir($symlinkPath)) {
    echo "⚠️  public/storage is a DIRECTORY (should be symlink)\n";
} else {
    echo "❌ Symlink DOES NOT EXIST\n";
}

echo "\n";

// Check if file is accessible through symlink
$publicPath = public_path('storage/' . $imagePath);
echo "Public path (through symlink): {$publicPath}\n";
if (file_exists($publicPath)) {
    echo "✅ File is ACCESSIBLE through symlink\n";
} else {
    echo "❌ File is NOT ACCESSIBLE through symlink\n";
}

echo "\n";

// Check using Storage facade
echo "Using Storage facade:\n";
try {
    $exists = \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath);
    echo "   Storage::disk('public')->exists(): " . ($exists ? 'Yes' : 'No') . "\n";
    
    if ($exists) {
        $url = asset('storage/' . $imagePath);
        echo "   Generated URL: {$url}\n";
    }
} catch (\Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";

// List files in alumni-posts directory
$alumniPostsDir = storage_path('app/public/alumni-posts');
echo "Files in alumni-posts directory:\n";
if (is_dir($alumniPostsDir)) {
    $files = glob($alumniPostsDir . '/*');
    echo "   Found " . count($files) . " files\n";
    foreach (array_slice($files, 0, 5) as $file) {
        echo "   - " . basename($file) . " (" . filesize($file) . " bytes)\n";
    }
    if (count($files) > 5) {
        echo "   ... and " . (count($files) - 5) . " more\n";
    }
} else {
    echo "   ❌ Directory does not exist\n";
}

echo "\n========================================\n";

