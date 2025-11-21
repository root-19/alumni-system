# Troubleshooting Image Display Issues

## Problem: Images showing 404 errors in Laravel Cloud deployment

### Quick Fix Steps:

1. **SSH into your Laravel Cloud server**
2. **Run the fix-permissions script:**
   ```bash
   chmod +x fix-permissions.sh
   ./fix-permissions.sh
   ```

### Manual Fix Steps:

If the script doesn't work, run these commands manually:

```bash
# 1. Create storage directories
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# 2. Remove broken symlink if exists
rm -f public/storage

# 3. Create storage symlink
php artisan storage:link

# 4. Set permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 5. Clear all caches
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

### Verify the Fix:

1. **Check if symlink exists:**
   ```bash
   ls -la public/storage
   ```
   Should show: `public/storage -> ../storage/app/public`

2. **Check if files exist:**
   ```bash
   ls -la storage/app/public/news_images
   ls -la storage/app/public/alumni-posts
   ```

3. **Check file permissions:**
   ```bash
   ls -la storage/app/public
   ```
   Files should be readable (644) and directories executable (755)

4. **Test image URL:**
   Visit: `https://your-domain.com/storage/news_images/filename.png`
   Should display the image, not 404

### Common Issues:

#### Issue 1: Symlink doesn't exist
**Solution:** Run `php artisan storage:link`

#### Issue 2: Symlink exists but points to wrong location
**Solution:** 
```bash
rm -f public/storage
php artisan storage:link
```

#### Issue 3: Files exist but permissions are wrong
**Solution:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### Issue 4: Images uploaded but not accessible
**Check:**
- Is `FILESYSTEM_DISK=public` in `.env`? (for local storage)
- Or is `FILESYSTEM_DISK=s3` with proper S3 credentials? (for S3 storage)
- Is the storage symlink created correctly?

#### Issue 5: Images work locally but not on deployment
**Solution:**
1. Make sure `deploy.sh` or `fix-permissions.sh` runs after deployment
2. Check that storage directories exist on server
3. Verify symlink is created
4. Clear all caches

### Laravel Cloud Specific:

If you're using Laravel Cloud:

1. **Check deployment logs** for errors
2. **SSH into the server** and verify:
   - Storage directories exist
   - Symlink is created
   - Permissions are correct
3. **Run the fix script** after each deployment if needed

### Flux.js Error (ui-modal already registered):

This is a JavaScript error, not related to images. It happens when:
- Flux.js is loaded multiple times
- Custom elements are registered twice

**Solution:** This is usually harmless but can be fixed by ensuring Flux.js is only loaded once in your layout file.

### Still Not Working?

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify `.env` file has correct `FILESYSTEM_DISK` setting
4. Test image URL directly in browser
5. Check if files actually exist in `storage/app/public/`

