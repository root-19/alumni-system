# Paano Gumagana ang Image Access

## Storage Structure:

```
storage/app/public/
├── news_images/
│   └── example.png
└── alumni-posts/
    └── example.png
```

## Symlink Setup:

Kapag nag-run ka ng `php artisan storage:link`, nagc-create ito ng symlink:

```
public/storage -> ../storage/app/public
```

## Paano Ma-access ang Images:

### 1. **Kapag nag-store ng image:**
```php
// Sa controller
$imagePath = $request->file('image')->store('news_images', 'public');
// Result: "news_images/example.png"
```

### 2. **I-save sa database:**
```php
News::create([
    'image_path' => 'news_images/example.png'  // Walang "storage/app/public/"
]);
```

### 3. **I-display sa view:**
```php
// Sa Blade template
asset('storage/' . $item->image_path)
// Result: "https://domain.com/storage/news_images/example.png"
```

### 4. **Actual file location:**
```
storage/app/public/news_images/example.png
```

### 5. **URL access:**
```
https://your-domain.com/storage/news_images/example.png
```

## Important Points:

✅ **Database `image_path`** = `news_images/example.png` (walang `storage/app/public/`)
✅ **`asset('storage/' . $path)`** = `https://domain.com/storage/news_images/example.png`
✅ **Symlink** = `public/storage` -> `storage/app/public`
✅ **Actual file** = `storage/app/public/news_images/example.png`

## Example:

**File stored at:**
```
storage/app/public/news_images/gB3TFsDoCiuA0SqeIGomvPB1IP0KfLmeOPR9bz0c.png
```

**Database `image_path`:**
```
news_images/gB3TFsDoCiuA0SqeIGomvPB1IP0KfLmeOPR9bz0c.png
```

**URL generated:**
```
https://your-domain.com/storage/news_images/gB3TFsDoCiuA0SqeIGomvPB1IP0KfLmeOPR9bz0c.png
```

**Symlink resolves to:**
```
public/storage/news_images/... -> storage/app/public/news_images/...
```

## Verification:

### Check if symlink exists:
```bash
ls -la public/storage
# Should show: public/storage -> ../storage/app/public
```

### Check if file exists:
```bash
ls -la storage/app/public/news_images/
```

### Test URL:
Visit: `https://your-domain.com/storage/news_images/example.png`
Should display the image!

## Current Implementation:

✅ All views use: `asset('storage/' . $image_path)` for local storage
✅ All views use: `Storage::disk('s3')->url($image_path)` for S3 storage
✅ Automatic detection based on `config('filesystems.default')`

## Troubleshooting:

**If images don't display:**

1. **Check symlink:**
   ```bash
   php artisan storage:link
   ```

2. **Check file exists:**
   ```bash
   ls -la storage/app/public/news_images/
   ```

3. **Check database:**
   ```php
   \App\Models\News::first()->image_path
   // Should be: "news_images/filename.png"
   ```

4. **Check URL:**
   ```php
   asset('storage/' . \App\Models\News::first()->image_path)
   // Should generate correct URL
   ```

