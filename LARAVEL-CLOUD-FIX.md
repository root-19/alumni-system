# Laravel Cloud - Symlink Fix Guide

## Problem:
404 error sa images: `https://alumni-systems-main-mrdkxr.laravel.cloud/storage/alumni-posts/...png`

## Solution:
I-run ang symlink fix script sa Laravel Cloud server.

## Step-by-Step Instructions:

### 1. SSH into Laravel Cloud Server

**Option A: Via Laravel Cloud Dashboard**
1. Login sa Laravel Cloud dashboard
2. Piliin ang project: `alumni-systems-main`
3. Click "SSH" o "Terminal" button
4. Makikita mo ang terminal/command prompt

**Option B: Via Command Line (kung may SSH access)**
```bash
ssh your-username@alumni-systems-main-mrdkxr.laravel.cloud
```

### 2. Navigate to Project Directory

```bash
cd /var/www/html
# O kung saan man naka-deploy ang project
# Usually: cd ~/project-name o cd /var/www/html
```

### 3. Upload/Check if Script Exists

**Check if script exists:**
```bash
ls -la fix-symlink-only.sh
```

**If wala, create it manually:**
```bash
nano fix-symlink-only.sh
# Copy-paste ang contents ng fix-symlink-only.sh
# Save: Ctrl+X, then Y, then Enter
```

**Or upload via Git:**
- Commit ang script sa Git
- Push to repository
- Laravel Cloud will auto-deploy

### 4. Make Script Executable

```bash
chmod +x fix-symlink-only.sh
```

### 5. Run the Script

```bash
./fix-symlink-only.sh
```

### 6. Verify it Worked

```bash
# Check symlink
ls -la public/storage
# Should show: public/storage -> ../storage/app/public

# Test if files are accessible
ls public/storage/alumni-posts/
# Should list your image files
```

## Alternative: Manual Fix (if script doesn't work)

Kung hindi gumana ang script, i-run manually:

```bash
# 1. Go to project root
cd /var/www/html  # or your project path

# 2. Remove existing symlink
rm -f public/storage
rm -rf public/storage

# 3. Create directories
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts

# 4. Create symlink
php artisan storage:link

# 5. If artisan fails, create manually
cd public
ln -s ../storage/app/public storage
cd ..

# 6. Set permissions
chmod -R 775 storage/app/public
chmod 755 public

# 7. Verify
ls -la public/storage
# Should show: public/storage -> ../storage/app/public

# 8. Clear caches
php artisan view:clear
php artisan config:clear
php artisan optimize:clear
```

## Test After Fix:

1. **Check symlink:**
   ```bash
   ls -la public/storage
   ```

2. **Test file access:**
   ```bash
   ls public/storage/alumni-posts/
   ```

3. **Test URL in browser:**
   ```
   https://alumni-systems-main-mrdkxr.laravel.cloud/storage/alumni-posts/vRmCDWcMPCC4xoD5NDdBqvvYYZILrGybbUBsTJcq.png
   ```

## Common Issues:

### Issue 1: "Permission denied"
```bash
chmod +x fix-symlink-only.sh
sudo ./fix-symlink-only.sh  # If needed
```

### Issue 2: "php artisan: command not found"
```bash
# Find PHP path
which php
# Or use full path
/usr/bin/php artisan storage:link
```

### Issue 3: "Cannot create symlink"
```bash
# Check if public/storage is a directory
ls -la public/storage
# If it's a directory, remove it first
rm -rf public/storage
# Then create symlink
php artisan storage:link
```

### Issue 4: Script not found
- Upload script via Git
- Or create it manually using `nano` or `vi`

## Quick One-Liner Fix:

Kung gusto mo ng quick fix, i-run lang ito:

```bash
rm -rf public/storage && mkdir -p storage/app/public/{news_images,alumni-posts} && php artisan storage:link && chmod -R 775 storage/app/public && php artisan view:clear
```

## After Deployment:

**Important:** Sa Laravel Cloud, kailangan i-run ang symlink command after every deployment kung hindi automatic.

**Solution:** Add to deployment script o post-deploy hook:
```bash
php artisan storage:link
```

