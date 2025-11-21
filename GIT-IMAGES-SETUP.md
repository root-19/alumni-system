# Git Setup para sa Images

## Problem:
Ang images sa `storage/app/public` ay hindi nakikita sa GitHub dahil sa `.gitignore`.

## Solution:
Na-update na ang `.gitignore` para ma-allow ang `storage/app/public` sa Git.

## Changes Made:

Sa `.gitignore`, na-add ang:
```
# Allow storage/app/public to be tracked (for images)
!/storage/app/public
!/storage/app/public/**
```

Ito ay nag-o-override ng default Laravel behavior na nag-i-ignore ng storage folders.

## Next Steps:

### 1. Add Images to Git:

```bash
# Check current status
git status

# Add storage/app/public directory
git add storage/app/public/

# Or add specific folders
git add storage/app/public/news_images/
git add storage/app/public/alumni-posts/
git add storage/app/public/profiles/
git add storage/app/public/donations/

# Commit
git commit -m "Add images to repository"

# Push to GitHub
git push
```

### 2. Verify sa GitHub:

After pushing, i-check sa GitHub:
- Dapat makita ang `storage/app/public/` folder
- Dapat may images sa `news_images/`, `alumni-posts/`, etc.

### 3. Sa Laravel Cloud Deployment:

After deployment, i-run:
```bash
php artisan storage:link
```

Para ma-create ang symlink from `public/storage` to `storage/app/public`.

## Important Notes:

1. **Symlink (`public/storage`)** - Dapat naka-ignore pa rin (symlink lang ito)
2. **Actual Files (`storage/app/public`)** - Dapat naka-track na sa Git
3. **File Size** - Large images ay maaaring maging issue sa Git
   - Consider using Git LFS para sa large files
   - O i-upload na lang via S3 storage

## Git LFS (Optional - for large files):

Kung maraming large images, consider Git LFS:

```bash
# Install Git LFS
git lfs install

# Track image files
git lfs track "*.png"
git lfs track "*.jpg"
git lfs track "*.jpeg"

# Add .gitattributes
git add .gitattributes
git commit -m "Add Git LFS tracking for images"
```

## Current Status:

✅ `.gitignore` updated - `storage/app/public` ay allowed na
✅ Images ay maaari nang i-commit sa Git
⚠️ Kailangan pa rin i-run ang `php artisan storage:link` sa deployment

