<?php

/**
 * Image Verification Script
 * Run this to check if images exist and are accessible
 * 
 * Usage: php verify-images.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AlumniPost;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

echo "========================================\n";
echo "Image Verification Script\n";
echo "========================================\n\n";

$defaultDisk = config('filesystems.default');
echo "Filesystem Default: {$defaultDisk}\n";
echo "Storage Path: " . storage_path('app/public') . "\n";
echo "Public Storage Symlink: " . public_path('storage') . "\n";
echo "Symlink Exists: " . (is_link(public_path('storage')) ? 'YES' : 'NO') . "\n";
if (is_link(public_path('storage'))) {
    echo "Symlink Target: " . readlink(public_path('storage')) . "\n";
}
echo "\n";

// Check Alumni Posts
echo "--- Alumni Posts Images ---\n";
$alumniPosts = AlumniPost::whereNotNull('image_path')->latest()->take(10)->get();

if ($alumniPosts->isEmpty()) {
    echo "No alumni posts with images found.\n\n";
} else {
    foreach ($alumniPosts as $post) {
        echo "ID: {$post->id}\n";
        echo "Image Path: {$post->image_path}\n";
        
        if ($defaultDisk === 's3') {
            $exists = Storage::disk('s3')->exists($post->image_path);
            $url = Storage::disk('s3')->url($post->image_path);
        } else {
            $exists = Storage::disk('public')->exists($post->image_path);
            $url = asset('storage/' . $post->image_path);
            $fullPath = storage_path('app/public/' . $post->image_path);
            $fileExists = file_exists($fullPath);
        }
        
        echo "Storage Exists: " . ($exists ? 'YES' : 'NO') . "\n";
        if ($defaultDisk !== 's3') {
            echo "File Exists: " . ($fileExists ? 'YES' : 'NO') . "\n";
            echo "Full Path: {$fullPath}\n";
        }
        echo "URL: {$url}\n";
        echo "---\n";
    }
}

echo "\n";

// Check News
echo "--- News Images ---\n";
$news = News::whereNotNull('image_path')->latest()->take(10)->get();

if ($news->isEmpty()) {
    echo "No news with images found.\n\n";
} else {
    foreach ($news as $item) {
        echo "ID: {$item->id}\n";
        echo "Image Path: {$item->image_path}\n";
        
        if ($defaultDisk === 's3') {
            $exists = Storage::disk('s3')->exists($item->image_path);
            $url = Storage::disk('s3')->url($item->image_path);
        } else {
            $exists = Storage::disk('public')->exists($item->image_path);
            $url = asset('storage/' . $item->image_path);
            $fullPath = storage_path('app/public/' . $item->image_path);
            $fileExists = file_exists($fullPath);
        }
        
        echo "Storage Exists: " . ($exists ? 'YES' : 'NO') . "\n";
        if ($defaultDisk !== 's3') {
            echo "File Exists: " . ($fileExists ? 'YES' : 'NO') . "\n";
            echo "Full Path: {$fullPath}\n";
        }
        echo "URL: {$url}\n";
        echo "---\n";
    }
}

echo "\n========================================\n";
echo "Verification Complete\n";
echo "========================================\n";

