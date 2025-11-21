<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Check if S3 is configured
     */
    public static function isS3Configured(): bool
    {
        return !empty(env('AWS_BUCKET')) && !empty(env('AWS_ACCESS_KEY_ID'));
    }

    /**
     * Get image URL with fallback
     * Tries S3 first if configured, then falls back to local storage
     */
    public static function getImageUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        // Try S3 first if configured
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
        if (Storage::disk('public')->exists($imagePath)) {
            return asset('storage/' . $imagePath);
        }

        return null;
    }

    /**
     * Check if image exists (either in S3 or local storage)
     */
    public static function imageExists(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        // Try S3 first if configured
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
