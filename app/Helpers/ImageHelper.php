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
        // Check individual env vars first
        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        
        // If individual vars not set, try parsing from CLOUDINARY_URL
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            $cloudinaryUrl = env('CLOUDINARY_URL');
            if ($cloudinaryUrl && preg_match('/cloudinary:\/\/([^:]+):([^@]+)@([^\/]+)/', $cloudinaryUrl, $matches)) {
                $apiKey = $apiKey ?: $matches[1];
                $apiSecret = $apiSecret ?: $matches[2];
                $cloudName = $cloudName ?: $matches[3];
            }
        }
        
        return !empty($cloudName) && !empty($apiKey) && !empty($apiSecret);
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
