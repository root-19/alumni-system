# How to Add Images to Git

## Current Setup:

✅ **Images are stored in:** `storage/app/public/`
- `news_images/` - News article images
- `alumni-posts/` - Event images  
- `alumni_images/` - Alumni profile images
- `profiles/` - User profile images
- `donations/` - Donation images
- `resumes/` - Resume PDFs
- `trainings/` - Training certificates and modules

✅ **Symlink:** `public/storage` → `../storage/app/public`
- Created by `php artisan storage:link`
- NOT tracked in Git (symlinks cause issues)
- Will be recreated on deployment

## Git Configuration:

### `.gitignore` (root):
- ✅ Allows `storage/app/public/` and all subdirectories
- ✅ Explicitly allows all image folders
- ⚠️ `/public/storage` is ignored (symlink, created on deployment)

### `storage/app/public/.gitignore`:
- Allows specific image folders
- Allows image file types: png, jpg, jpeg, gif, svg, webp, pdf

## Commands to Add Images:

```bash
# Check what images are not tracked
git status storage/app/public/

# Add all images
git add storage/app/public/

# Or add specific folders
git add storage/app/public/news_images/
git add storage/app/public/alumni-posts/
git add storage/app/public/alumni_images/
git add storage/app/public/profiles/
git add storage/app/public/donations/
git add storage/app/public/resumes/
git add storage/app/public/trainings/

# Commit
git commit -m "Add images to repository"

# Push to GitHub
git push
```

## Verify Images are Tracked:

```bash
# List all tracked images
git ls-files storage/app/public/

# Count tracked images
git ls-files storage/app/public/ | Measure-Object -Line

# Check specific folder
git ls-files storage/app/public/news_images/
```

## Important Notes:

1. **`public/storage` is a SYMLINK:**
   - It's NOT a real folder, it's a link to `storage/app/public`
   - Created by: `php artisan storage:link`
   - Should NOT be tracked in Git
   - Will be recreated automatically on deployment

2. **Actual Images Location:**
   - Real images are in: `storage/app/public/`
   - These ARE tracked in Git
   - Accessible via: `https://your-domain.com/storage/news_images/example.png`

3. **After Deployment:**
   ```bash
   # Run this to create the symlink
   php artisan storage:link
   ```

## Current Image Folders:

Based on your storage, you have:
- ✅ `news_images/` - 13 images
- ✅ `alumni-posts/` - 8 images  
- ✅ `alumni_images/` - 8 images
- ✅ `profiles/` - 11 images
- ✅ `donations/` - 8 images
- ✅ `resumes/` - 8 PDFs
- ✅ `trainings/` - Multiple folders with certificates and modules

All of these should now be tracked in Git!

