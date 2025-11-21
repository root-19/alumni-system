# Storage Symlink Setup Guide

## Problem

When deploying a Laravel application to the cloud, images stored in `storage/app/public` may not display due to the absence of a symbolic link from `public/storage` to `storage/app/public`.

This link is essential for web accessibility, as Laravel's public disk stores files in `storage/app/public` by default, and the web server needs a symlink to serve them.

## Solution: Temporary Route Method

If you don't have direct command-line access to your server, you can use a temporary route to programmatically create the symlink.

### Step 1: Set a Secret Token (Optional but Recommended)

Add this to your `.env` file on the server:

```env
STORAGE_LINK_TOKEN=your-secret-random-token-here
```

Or the route will use a default token: `change-this-token-before-deploying`

### Step 2: Access the Route

Visit this URL in your browser (replace `your-secret-token` with your actual token):

```
https://your-domain.com/create-storage-link?token=your-secret-token
```

Or if you didn't set a token:

```
https://your-domain.com/create-storage-link?token=change-this-token-before-deploying
```

### Step 3: Verify the Response

You should see a JSON response like:

```json
{
  "success": true,
  "message": "Storage symlink created successfully!",
  "details": [
    "✓ Target directory exists: /var/www/html/storage/app/public",
    "✓ Symlink created successfully using artisan",
    "Link: ../storage/app/public",
    "✓ Verification: Symlink exists",
    "✓ Symlink resolves correctly - images should now be accessible!"
  ],
  "link_path": "/var/www/html/public/storage",
  "target_path": "/var/www/html/storage/app/public",
  "warning": "⚠ IMPORTANT: Remove this route from routes/web.php after verifying it works!"
}
```

### Step 4: Test Image Display

1. Upload a test image through your application
2. Check if the image displays correctly
3. Verify the image URL starts with `https://your-domain.com/storage/...`

### Step 5: Remove the Route (IMPORTANT!)

**After verifying the symlink works, you MUST remove the route for security!**

1. Open `routes/web.php`
2. Find and delete the route that starts with:
   ```php
   Route::get('/create-storage-link', function (Request $request) {
   ```
3. Remove everything until `require __DIR__ . '/auth.php';`
4. Save the file

## Alternative: Using SSH/Command Line

If you have SSH access, you can use the standard Laravel command:

```bash
# Remove existing symlink/directory if it exists
rm -f public/storage
rm -rf public/storage

# Create the symlink
php artisan storage:link

# Verify it was created
ls -la public/storage
```

## What the Route Does

1. **Checks for existing symlink/directory** - Removes it if it's broken or incorrect
2. **Creates target directory** - Ensures `storage/app/public` exists
3. **Creates symlink** - Uses `php artisan storage:link` or manual symlink creation
4. **Verifies symlink** - Confirms the symlink works correctly

## Security Notes

- ⚠️ **This route should be removed immediately after use!**
- The route requires a token parameter for basic security
- Change the default token in production
- Consider adding IP whitelist or admin authentication for extra security

## Troubleshooting

### Route returns 401 Unauthorized
- Make sure you're including the `?token=...` parameter
- Check that the token matches what's in your `.env` file

### Route returns 500 Error
- Check file permissions on `storage/app/public` and `public/` directories
- Ensure the web server has write permissions
- Check Laravel logs for detailed error messages

### Symlink created but images still don't display
- Clear Laravel caches: `php artisan cache:clear && php artisan config:clear`
- Verify the symlink points to the correct location: `ls -la public/storage`
- Check web server configuration allows following symlinks
- Verify file permissions: `chmod -R 775 storage/app/public`

## Manual Symlink Creation (If Route Fails)

If the route doesn't work, you can create the symlink manually via SSH:

```bash
cd /path/to/your/laravel/project
rm -rf public/storage
ln -s ../storage/app/public public/storage
chmod -R 775 storage/app/public
```

## Verification Commands

After creating the symlink, verify it works:

```bash
# Check if symlink exists
ls -la public/storage

# Should show: public/storage -> ../storage/app/public

# Test if symlink resolves
ls public/storage/

# Should list files from storage/app/public
```

