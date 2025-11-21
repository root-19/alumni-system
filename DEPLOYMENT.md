# Deployment Instructions

## File Permissions Fix

If images are not displaying after deployment, it's likely a file permissions issue. Follow these steps:

### Option 1: Use the fix-permissions.sh script (Recommended)

On your deployment server (Laravel Cloud), run:

```bash
chmod +x fix-permissions.sh
./fix-permissions.sh
```

Or if you need sudo:

```bash
chmod +x fix-permissions.sh
sudo ./fix-permissions.sh
```

### Option 2: Manual Commands

Run these commands on your server:

```bash
# Create storage directories
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create storage symlink
php artisan storage:link

# Clear caches
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
```

### Option 3: Use deploy.sh (After deployment)

```bash
chmod +x deploy.sh
./deploy.sh
```

## Laravel Cloud Specific

If you're using Laravel Cloud, you can:

1. SSH into your server
2. Navigate to your project directory
3. Run the `fix-permissions.sh` script
4. Or run the commands manually

## Troubleshooting

### Images still not displaying?

1. **Check if storage symlink exists:**
   ```bash
   ls -la public/storage
   ```
   Should show a symlink to `../storage/app/public`

2. **Check file permissions:**
   ```bash
   ls -la storage/app/public
   ```
   Files should be readable (644) and directories should be executable (755)

3. **Check if files exist:**
   ```bash
   ls -la storage/app/public/news_images
   ls -la storage/app/public/alumni-posts
   ```

4. **Verify .env configuration:**
   Make sure `FILESYSTEM_DISK=public` for local storage, or `FILESYSTEM_DISK=s3` for S3 storage

5. **Clear all caches:**
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan view:clear
   ```

## Image Storage

- **Local Storage:** Images are stored in `storage/app/public/news_images` and `storage/app/public/alumni-posts`
- **S3 Storage:** Images are stored in your S3 bucket (configured via .env)
- The `public/storage` symlink makes local storage files accessible via web

