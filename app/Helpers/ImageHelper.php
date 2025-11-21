<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL - works for both local storage and S3
     */
    public static function url($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }

        $defaultDisk = config('filesystems.default');
        
        // For S3 storage
        if ($defaultDisk === 's3') {
            return Storage::disk('s3')->url($imagePath);
        }
        
        // For local storage - use asset() which works with symlink
        // This generates: https://domain.com/storage/path/to/image.png
        return asset('storage/' . $imagePath);
    }
    
    /**
     * Check if image exists
     */
    public static function exists($imagePath)
    {
        if (empty($imagePath)) {
            return false;
        }
        
        $defaultDisk = config('filesystems.default');
        return Storage::disk($defaultDisk)->exists($imagePath);
    }
    
    /**
     * Get image URL with fallback - tries multiple methods
     */
    public static function urlWithFallback($imagePath)
    {
        if (empty($imagePath)) {
            return null;
        }
        
        $defaultDisk = config('filesystems.default');
        
        // For S3 storage
        if ($defaultDisk === 's3') {
            return Storage::disk('s3')->url($imagePath);
        }
        
        // For local storage - try asset() first (uses symlink)
        $url = asset('storage/' . $imagePath);
        
        // Verify file exists
        if (Storage::disk('public')->exists($imagePath)) {
            return $url;
        }
        
        // Fallback: try direct storage URL
        return Storage::disk('public')->url($imagePath);
    }
}

