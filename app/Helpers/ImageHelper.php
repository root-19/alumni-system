<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Check if Cloudinary is configured
     */
    public static function isCloudinaryConfigured(): bool
    {
        // Check filesystem config first (uses cached config values)
        $cloudinaryConfig = config('filesystems.disks.cloudinary');
        $cloudName = null;
        $apiKey = null;
        $apiSecret = null;
        
        if ($cloudinaryConfig) {
            $cloudName = $cloudinaryConfig['cloud_name'] ?? null;
            $apiKey = $cloudinaryConfig['api_key'] ?? null;
            $apiSecret = $cloudinaryConfig['api_secret'] ?? null;
        }
        
        // If config values are empty, check env vars directly
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            $cloudName = $cloudName ?: env('CLOUDINARY_CLOUD_NAME');
            $apiKey = $apiKey ?: env('CLOUDINARY_API_KEY');
            $apiSecret = $apiSecret ?: env('CLOUDINARY_API_SECRET');
            
            // If individual vars not set, try parsing from CLOUDINARY_URL
            if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
                $cloudinaryUrl = env('CLOUDINARY_URL');
                if ($cloudinaryUrl && preg_match('/cloudinary:\/\/([^:]+):([^@]+)@([^\/]+)/', $cloudinaryUrl, $matches)) {
                    $apiKey = $apiKey ?: $matches[1];
                    $apiSecret = $apiSecret ?: $matches[2];
                    $cloudName = $cloudName ?: $matches[3];
                }
            }
        }
        
        $isConfigured = !empty($cloudName) && !empty($apiKey) && !empty($apiSecret);
        
        // Debug logging
        \Log::info('Cloudinary configuration check:', [
            'cloud_name' => $cloudName ? substr($cloudName, 0, 3) . '...' : 'not set',
            'api_key' => $apiKey ? substr($apiKey, 0, 3) . '...' : 'not set',
            'api_secret' => $apiSecret ? '***set***' : 'not set',
            'is_configured' => $isConfigured,
            'config_disk_exists' => !empty($cloudinaryConfig),
            'has_cloudinary_url' => !empty(env('CLOUDINARY_URL')),
        ]);
        
        return $isConfigured;
    }

    /**
     * Check if S3 is configured
     */
    public static function isS3Configured(): bool
    {
        return !empty(env('AWS_BUCKET')) && !empty(env('AWS_ACCESS_KEY_ID'));
    }

    /**
     * Get image URL with fallback
     * Tries Cloudinary first, then S3, then local storage
     */
    public static function getImageUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        // Try Cloudinary first if configured
        if (self::isCloudinaryConfigured()) {
            try {
                return Storage::disk('cloudinary')->url($imagePath);
            } catch (\Exception $e) {
                // Cloudinary error, fall through to other options
            }
        }

        // Try S3 if configured
        if (self::isS3Configured()) {
            try {
                if (Storage::disk('s3')->exists($imagePath)) {
                    return Storage::disk('s3')->url($imagePath);
                }
            } catch (\Exception $e) {
                // S3 error, fall through to local storage
            }
        }

        // Fallback to local storage
        return asset('storage/' . $imagePath);
    }

    /**
     * Check if image exists (Cloudinary, S3, or local storage)
     */
    public static function imageExists(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        // Try Cloudinary first if configured
        if (self::isCloudinaryConfigured()) {
            try {
                if (Storage::disk('cloudinary')->exists($imagePath)) {
                    return true;
                }
            } catch (\Exception $e) {
                // Cloudinary error, fall through
            }
        }

        // Try S3 if configured
        if (self::isS3Configured()) {
            try {
                if (Storage::disk('s3')->exists($imagePath)) {
                    return true;
                }
            } catch (\Exception $e) {
                // S3 error, fall through to local storage
            }
        }

        // Fallback to local storage
        return Storage::disk('public')->exists($imagePath);
    }
}
