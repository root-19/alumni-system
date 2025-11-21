# Git Setup for Storage Images

## Current Setup:

### 1. Images are stored in:
```
storage/app/public/
├── news_images/
├── alumni-posts/
├── alumni_images/
├── profiles/
├── donations/
├── resumes/
└── trainings/
```

### 2. Symlink:
```
public/storage -> ../storage/app/public
```

## Git Configuration:

### `.gitignore` (root):
- ✅ Allows `storage/app/public/` and all subdirectories
- ✅ Allows all image folders explicitly
- ⚠️ `/public/storage` is commented out (symlink will be created by `php artisan storage:link`)

### `storage/app/public/.gitignore`:
- Allows specific image folders
- Allows common image file types (png, jpg, jpeg, gif, svg, webp, pdf)

## To Add Images to Git:

```bash
# Add all images
git add storage/app/public/

# Or add specific folders
git add storage/app/public/news_images/
git add storage/app/public/alumni-posts/

# Commit
git commit -m "Add images to repository"

# Push
git push
```

## Important Notes:

1. **Symlink (`public/storage`):**
   - The symlink is created by `php artisan storage:link`
   - It's not tracked in Git (symlinks can cause issues)
   - Will be recreated on deployment

2. **Actual Images:**
   - All images in `storage/app/public/` are tracked
   - Make sure to commit and push them

3. **After Deployment:**
   - Run `php artisan storage:link` to create the symlink
   - Images will be accessible via `/storage/` URL

## Verify Images are Tracked:

```bash
# Check if images are tracked
git ls-files storage/app/public/

# Check if specific image exists
git ls-files storage/app/public/news_images/
```

