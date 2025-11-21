#!/bin/bash

# Quick Symlink Fix Script for Laravel Cloud
# Run this if images are showing 404 errors

echo "=========================================="
echo "Laravel Storage Symlink Fix"
echo "=========================================="
echo ""

# Check current status
echo "Checking current symlink status..."
if [ -L "public/storage" ]; then
    echo "✓ Symlink exists"
    LINK_TARGET=$(readlink -f public/storage 2>/dev/null || readlink public/storage)
    echo "  Current target: $LINK_TARGET"
    
    # Check if target exists
    if [ -d "$LINK_TARGET" ] || [ -d "storage/app/public" ]; then
        echo "✓ Target directory exists"
    else
        echo "⚠ Target directory does not exist"
    fi
elif [ -d "public/storage" ]; then
    echo "⚠ public/storage is a directory (should be symlink)"
    echo "  This will be removed and replaced with symlink"
else
    echo "❌ Symlink does not exist"
fi
echo ""

# Remove existing symlink or directory
echo "Removing existing public/storage..."
rm -f public/storage
rm -rf public/storage
echo "✓ Removed"
echo ""

# Ensure target directory exists
echo "Ensuring storage directories exist..."
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
chmod -R 775 storage/app/public
echo "✓ Directories created"
echo ""

# Create symlink using artisan
echo "Creating symlink using artisan..."
php artisan storage:link

# Verify
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink -f public/storage 2>/dev/null || readlink public/storage)
    echo "✓ Symlink created successfully"
    echo "  public/storage -> $LINK_TARGET"
    
    # Test if it resolves correctly
    if [ -d "public/storage" ]; then
        echo "✓ Symlink resolves correctly"
        
        # Check if we can see files through symlink
        if [ -d "public/storage/alumni-posts" ]; then
            FILE_COUNT=$(find public/storage/alumni-posts -name "*.png" 2>/dev/null | wc -l)
            echo "✓ Can access files through symlink"
            echo "  Found $FILE_COUNT PNG files in alumni-posts"
        fi
    else
        echo "⚠ Symlink created but does not resolve correctly"
    fi
else
    echo "❌ Failed to create symlink with artisan"
    echo "  Attempting manual creation..."
    cd public && ln -s ../storage/app/public storage && cd ..
    
    if [ -L "public/storage" ]; then
        echo "✓ Manual symlink creation successful"
    else
        echo "❌ Manual creation also failed"
        echo "  Please check permissions and run manually:"
        echo "  cd public && ln -s ../storage/app/public storage"
    fi
fi
echo ""

# Set permissions
echo "Setting permissions..."
chmod -R 775 storage/app/public
chmod 755 public
if [ -L "public/storage" ]; then
    chmod 755 public/storage
fi
echo "✓ Permissions set"
echo ""

# Clear caches
echo "Clearing caches..."
php artisan view:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
echo "✓ Caches cleared"
echo ""

echo "=========================================="
echo "Symlink fix completed!"
echo "=========================================="
echo ""
echo "Test by visiting:"
echo "https://your-domain.com/storage/alumni-posts/vRmCDWcMPCC4xoD5NDdBqvvYYZILrGybbUBsTJcq.png"
echo ""

