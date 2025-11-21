# Quick Fix - Images Not Displaying

## Problem:
Images are in database but not displaying on the page.

## Based on Logs:
- Image paths exist: `alumni-posts/vRmCDWcMPCC4xoD5NDdBqvvYYZILrGybbUBsTJcq.png`
- But images not showing on page

## Quick Fix Steps:

### 1. Run Verification Script (to see what's wrong):
```bash
php verify-images.php
```

This will show:
- If symlink exists
- If files actually exist in storage
- Generated URLs
- File existence status

### 2. Check Symlink:
```bash
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

If missing or broken:
```bash
rm -f public/storage
php artisan storage:link
```

### 3. Check if Files Exist:
```bash
ls -la storage/app/public/alumni-posts/
# Should see your image files
```

### 4. Check Permissions:
```bash
chmod -R 775 storage/app/public
chmod -R 775 public/storage
```

### 5. Check Browser Console:
Open browser DevTools (F12) → Console tab
Look for errors like:
- `Image failed to load: https://domain.com/storage/alumni-posts/...`

### 6. Test Direct URL:
Visit directly in browser:
```
https://your-domain.com/storage/alumni-posts/vRmCDWcMPCC4xoD5NDdBqvvYYZILrGybbUBsTJcq.png
```

**If 404:**
- Symlink is broken or missing
- Run: `php artisan storage:link`

**If 403 (Forbidden):**
- Permissions issue
- Run: `chmod -R 775 storage/app/public`

**If 200 (shows image):**
- URL is correct, issue is in view rendering
- Check browser console for JavaScript errors

## After Running verify-images.php:

The script will tell you:
1. ✅ **File Exists: YES** → Problem is URL generation or symlink
2. ❌ **File Exists: NO** → Files were not uploaded correctly
3. ✅ **Symlink Exists: YES** → Check permissions
4. ❌ **Symlink Exists: NO** → Run `php artisan storage:link`

## Most Common Fix:

```bash
# Remove broken symlink
rm -f public/storage

# Create new symlink
php artisan storage:link

# Set permissions
chmod -R 775 storage/app/public
chmod -R 775 public/storage

# Clear caches
php artisan optimize:clear
php artisan view:clear
```

## Check Logs After Fix:

After refreshing the page, check logs:
```bash
tail -n 50 storage/logs/laravel.log | grep "Dashboard"
```

Look for:
- `file_exists: true` or `file_exists: false`
- `image_url`: The generated URL
- `filesystem`: Should be `public` for local storage

## If Still Not Working:

1. **Check .env:**
   ```
   FILESYSTEM_DISK=public
   ```

2. **Verify file actually uploaded:**
   ```bash
   ls -lh storage/app/public/alumni-posts/
   # Should show file sizes
   ```

3. **Check web server can access:**
   ```bash
   curl -I https://your-domain.com/storage/alumni-posts/vRmCDWcMPCC4xoD5NDdBqvvYYZILrGybbUBsTJcq.png
   # Should return 200 OK
   ```

