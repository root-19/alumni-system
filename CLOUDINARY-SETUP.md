# Cloudinary Setup Guide

## Installation
✅ Cloudinary Laravel package has been installed via Composer.

## Configuration

### Step 1: Add Cloudinary credentials to `.env` file

Add these lines to your `.env` file:

```env
CLOUDINARY_CLOUD_NAME=dqotiddly
CLOUDINARY_API_KEY=519898696838735
CLOUDINARY_API_SECRET=1qAI_ytaX8PIYIjuH9O5OC5P5us
CLOUDINARY_URL=cloudinary://519898696838735:1qAI_ytaX8PIYIjuH9O5OC5P5us@dqotiddly
```

### Step 2: Update Filesystem Configuration

The `config/filesystems.php` has been updated to include Cloudinary disk configuration.

### Step 3: Clear Config Cache

After updating `.env`, run:
```bash
php artisan config:clear
php artisan config:cache
```

## How It Works

### Image Upload Priority:
1. **Cloudinary** (if configured) - Primary storage
2. **Local Storage** (fallback) - If Cloudinary is not configured

### Controllers Updated:
- ✅ `NewsController` - Uses Cloudinary for news images
- ✅ `AlumniController` - Uses Cloudinary for event images (store & update methods)

### ImageHelper Updated:
- ✅ `ImageHelper::getImageUrl()` - Tries Cloudinary first, then S3, then local storage
- ✅ `ImageHelper::isCloudinaryConfigured()` - Checks if Cloudinary is configured

## Benefits of Cloudinary:

1. **Automatic Image Optimization** - Images are automatically optimized for web
2. **CDN Delivery** - Fast global delivery via Cloudinary's CDN
3. **Image Transformations** - On-the-fly image resizing, cropping, etc.
4. **No Symlink Issues** - No need for symlinks, images are served directly from Cloudinary
5. **Scalable** - Handles high traffic without server load

## Testing:

After setting up `.env` credentials:

1. Upload a news image - should upload to Cloudinary
2. Upload an event image - should upload to Cloudinary
3. Check logs for "Storing image to Cloudinary" messages
4. Images should display with Cloudinary URLs (res.cloudinary.com)

## Troubleshooting:

### Images not uploading to Cloudinary?
- Check `.env` file has correct credentials
- Run `php artisan config:clear`
- Check logs for error messages

### Images not displaying?
- Cloudinary URLs should start with `https://res.cloudinary.com/`
- Check if image_path in database contains Cloudinary path
- Verify Cloudinary credentials are correct

## Migration from Local Storage:

Existing images in local storage will continue to work. New uploads will go to Cloudinary.

To migrate existing images:
1. Upload them again through the admin panel
2. Or use Cloudinary's upload API to migrate in bulk

