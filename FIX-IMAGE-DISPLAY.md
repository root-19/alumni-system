# Fix Image Display Issues

## Problem Summary

1. **Cloudinary not configured on server** - Environment variables are not set in Laravel Cloud dashboard
2. **Images stored locally** - Since Cloudinary isn't configured, images are being stored in local storage
3. **Images not displaying** - Views need to use `ImageHelper::getImageUrl()` to properly display images

## Changes Made

### ✅ Updated Views to Use ImageHelper

1. **`resources/views/news.blade.php`**
   - Updated hero image to use `ImageHelper::getImageUrl()`
   - Updated news list images to use `ImageHelper::getImageUrl()`
   - Updated alumni posts images to use `ImageHelper::getImageUrl()`

2. **`resources/views/admin/news.blade.php`**
   - Updated hero image to use `ImageHelper::getImageUrl()`
   - Updated news list images to use `ImageHelper::getImageUrl()`
   - Updated alumni posts images to use `ImageHelper::getImageUrl()`

### ✅ ImageHelper Already Updated
- `ImageHelper::isCloudinaryConfigured()` - Checks config and env vars
- `ImageHelper::getImageUrl()` - Returns correct URL (Cloudinary → S3 → Local)

## What You Need to Do

### Step 1: Add Environment Variables on Laravel Cloud

**Go to Laravel Cloud Dashboard:**
1. Navigate to your project
2. Go to **Settings** → **Environment Variables** (or **Environment**)
3. Add these variables:

```
CLOUDINARY_CLOUD_NAME=dqotiddly
CLOUDINARY_API_KEY=519898696838735
CLOUDINARY_API_SECRET=1qAI_ytaX8PIYIjuH9O5OC5P5us
CLOUDINARY_URL=cloudinary://519898696838735:1qAI_ytaX8PIYIjuH9O5OC5P5us@dqotiddly
```

### Step 2: Clear Config Cache on Server

SSH into your Laravel Cloud server and run:

```bash
php artisan config:clear
php artisan config:cache
```

### Step 3: Verify Storage Symlink (For Local Images)

If images are stored locally, make sure the storage symlink exists:

```bash
php artisan storage:link
```

### Step 4: Test Image Upload

1. Upload a new news image
2. Check logs - you should see:
   - `"Cloudinary configuration check:"` - shows if Cloudinary is detected
   - `"Storing image to Cloudinary"` - if Cloudinary is configured
   - `"Image stored successfully to Cloudinary"` - confirms upload

### Step 5: Check Image Display

Images should now display correctly because:
- Views use `ImageHelper::getImageUrl()` which handles all storage types
- Falls back to local storage if Cloudinary/S3 not available
- Includes error handling with fallback URLs

## How It Works Now

### Image Upload Flow:
1. Check if Cloudinary is configured → Upload to Cloudinary
2. If not → Upload to local storage

### Image Display Flow:
1. Try Cloudinary URL (if image stored in Cloudinary)
2. Try S3 URL (if image stored in S3)
3. Fallback to local storage URL (`asset('storage/...')`)

## Debugging

### Check Logs After Upload:
```
Cloudinary configuration check:
- cloud_name: set/not set
- api_key: set/not set
- api_secret: set/not set
- is_configured: true/false
```

### If Images Still Not Displaying:

1. **Check if storage symlink exists:**
   ```bash
   ls -la public/storage
   # Should show: public/storage -> ../storage/app/public
   ```

2. **Check if image file exists:**
   ```bash
   ls -la storage/app/public/news_images/
   ```

3. **Check browser console** - Look for 404 errors on image URLs

4. **Check Laravel logs** - Look for any errors when trying to generate image URLs

## Expected Behavior

### Before Fix:
- Images stored locally but trying to display from Cloudinary
- Broken image icons
- 404 errors in browser console

### After Fix:
- Images display correctly from local storage (until Cloudinary is configured)
- Once Cloudinary is configured, new images upload to Cloudinary
- All images display correctly regardless of storage location

