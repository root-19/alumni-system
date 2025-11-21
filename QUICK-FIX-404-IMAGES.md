# Quick Fix for 404 Image Errors on Laravel Cloud

## Problem:
- Images show `file_exists: true` in logs
- URLs are generated correctly
- But browser gets 404 errors
- **Root cause: Broken or missing symlink**

## Solution:

### Step 1: SSH into Laravel Cloud
```bash
# Connect to your Laravel Cloud server
ssh your-server
cd /var/www/html  # or your project path
```

### Step 2: Run the fix script
```bash
chmod +x fix-symlink-laravel-cloud.sh
./fix-symlink-laravel-cloud.sh
```

### Step 3: Or run these commands manually:

```bash
# Remove existing symlink/directory
rm -rf public/storage

# Ensure directories exist
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
chmod -R 775 storage/app/public

# Create symlink
php artisan storage:link

# If artisan fails, create manually:
cd public
ln -s ../storage/app/public storage
cd ..

# Verify symlink
ls -la public/storage
# Should show: public/storage -> ../storage/app/public

# Set permissions
chmod -R 775 storage/app/public
chmod 755 public

# Clear caches
php artisan view:clear
php artisan config:clear
php artisan optimize:clear
```

### Step 4: Test the URLs
Visit these URLs in your browser:
```
https://alumni-systems-main-mrdkxr.laravel.cloud/storage/alumni-posts/2r168mGXG0mLnK8EhrpEPItUAK40BqWp2iqa1XWU.jpg
https://alumni-systems-main-mrdkxr.laravel.cloud/storage/alumni-posts/z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg
```

If they work, images should now display!

## One-Liner Quick Fix:
```bash
rm -rf public/storage && mkdir -p storage/app/public/{news_images,alumni-posts} && php artisan storage:link && chmod -R 775 storage/app/public && php artisan view:clear && php artisan optimize:clear
```

## After Every Deployment:
**Important:** On Laravel Cloud, you need to run `php artisan storage:link` after every deployment if it's not automatic.

Add this to your deployment script or post-deploy hook.

