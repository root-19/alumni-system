# Debug Guide - Image Display Issues

## Debug Statements Added

I've added comprehensive logging to help identify the problem:

### Controllers with Debug Logging:

1. **NewsController@store** - Logs image upload and storage
2. **AlumniController@store** - Logs event image upload and storage
3. **DashboardController@index** - Logs all data being passed to dashboard view

### How to Check Logs:

1. **On Laravel Cloud, SSH into your server:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Or view the last 100 lines:**
   ```bash
   tail -n 100 storage/logs/laravel.log
   ```

3. **Search for specific debug messages:**
   ```bash
   grep "News Store Request" storage/logs/laravel.log
   grep "Alumni Post Store Request" storage/logs/laravel.log
   grep "Dashboard" storage/logs/laravel.log
   ```

### What to Look For:

#### When Creating News/Events:
- `has_image`: Should be `true` if image was uploaded
- `filesystem_default`: Should be `public` or `s3`
- `path`: The stored image path
- `exists`: Should be `true` if file exists after storage

#### When Viewing Dashboard:
- `image_paths`: Array of all image paths from database
- `filesystem_default`: Current filesystem configuration
- `count`: Number of events/news items

### Common Issues to Check:

1. **Image not stored:**
   - Check if `has_image` is `true`
   - Check if `exists` is `true` after storage
   - Check filesystem permissions

2. **Image path incorrect:**
   - Check `image_path` in database
   - Verify path matches storage location
   - Check if symlink exists: `ls -la public/storage`

3. **Filesystem mismatch:**
   - If `filesystem_default` is `s3` but no S3 credentials
   - If `filesystem_default` is `public` but symlink missing

### Quick Debug Commands:

```bash
# Check if images exist in storage
ls -la storage/app/public/news_images/
ls -la storage/app/public/alumni-posts/

# Check symlink
ls -la public/storage

# Check filesystem config
php artisan tinker
>>> config('filesystems.default')

# Check database image paths
php artisan tinker
>>> \App\Models\News::latest()->first()->image_path
>>> \App\Models\AlumniPost::latest()->first()->image_path
```

### Temporary DD() Debugging:

If you need to see data immediately, you can temporarily add `dd()` statements:

```php
// In controller
dd([
    'image_path' => $imagePath,
    'filesystem' => config('filesystems.default'),
    'exists' => Storage::disk(config('filesystems.default'))->exists($imagePath),
]);

// In view
@php
    dd([
        'image_path' => $event->image_path,
        'url' => $imageUrl,
        'filesystem' => config('filesystems.default'),
    ]);
@endphp
```

**Remember to remove `dd()` statements after debugging!**

