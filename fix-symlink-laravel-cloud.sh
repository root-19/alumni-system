#!/bin/bash

# Fix Symlink for Laravel Cloud
# This script specifically addresses 404 errors for images
# Run this on Laravel Cloud after deployment

echo "=========================================="
echo "Fixing Storage Symlink for Laravel Cloud"
echo "=========================================="
echo ""

# Get the absolute path
PROJECT_ROOT=$(pwd)
echo "Project root: $PROJECT_ROOT"
echo ""

# Step 1: Remove existing symlink or directory
echo "Step 1: Removing existing public/storage..."
if [ -L "public/storage" ]; then
    echo "  Found existing symlink, removing..."
    rm -f public/storage
elif [ -d "public/storage" ]; then
    echo "  Found directory (should be symlink), removing..."
    rm -rf public/storage
else
    echo "  No existing symlink or directory found"
fi
echo "✓ Removed"
echo ""

# Step 2: Ensure storage directories exist
echo "Step 2: Ensuring storage directories exist..."
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
mkdir -p storage/app/public/alumni_images
mkdir -p storage/app/public/profiles
chmod -R 775 storage/app/public
echo "✓ Directories created"
echo ""

# Step 3: Create symlink using artisan
echo "Step 3: Creating symlink using artisan..."
php artisan storage:link

# Verify symlink was created
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink public/storage)
    echo "✓ Symlink created successfully"
    echo "  Link: public/storage -> $LINK_TARGET"
    
    # Check if symlink resolves
    if [ -d "public/storage" ]; then
        echo "✓ Symlink resolves correctly"
        
        # Test if we can access files
        if [ -d "public/storage/alumni-posts" ]; then
            FILE_COUNT=$(find public/storage/alumni-posts -type f 2>/dev/null | wc -l)
            echo "✓ Can access files through symlink"
            echo "  Found $FILE_COUNT files in alumni-posts"
        fi
    else
        echo "⚠ WARNING: Symlink exists but does not resolve!"
        echo "  Attempting manual fix..."
        rm -f public/storage
        cd public && ln -s ../storage/app/public storage && cd ..
    fi
else
    echo "❌ Failed to create symlink with artisan"
    echo "  Attempting manual creation..."
    cd public && ln -s ../storage/app/public storage && cd ..
    
    if [ -L "public/storage" ]; then
        echo "✓ Manual symlink creation successful"
    else
        echo "❌ Manual creation also failed"
        echo "  Please check permissions"
    fi
fi
echo ""

# Step 4: Set proper permissions
echo "Step 4: Setting permissions..."
chmod -R 775 storage/app/public
chmod 755 public
if [ -L "public/storage" ]; then
    chmod 755 public/storage
fi
echo "✓ Permissions set"
echo ""

# Step 5: Clear all caches
echo "Step 5: Clearing caches..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
echo "✓ Caches cleared"
echo ""

# Step 6: Verify specific image files
echo "Step 6: Verifying image files..."
if [ -f "storage/app/public/alumni-posts/2r168mGXG0mLnK8EhrpEPItUAK40BqWp2iqa1XWU.jpg" ]; then
    echo "✓ File exists: 2r168mGXG0mLnK8EhrpEPItUAK40BqWp2iqa1XWU.jpg"
    if [ -f "public/storage/alumni-posts/2r168mGXG0mLnK8EhrpEPItUAK40BqWp2iqa1XWU.jpg" ]; then
        echo "✓ Accessible via symlink"
    else
        echo "❌ NOT accessible via symlink"
    fi
else
    echo "⚠ File not found: 2r168mGXG0mLnK8EhrpEPItUAK40BqWp2iqa1XWU.jpg"
fi

if [ -f "storage/app/public/alumni-posts/z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg" ]; then
    echo "✓ File exists: z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg"
    if [ -f "public/storage/alumni-posts/z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg" ]; then
        echo "✓ Accessible via symlink"
    else
        echo "❌ NOT accessible via symlink"
    fi
else
    echo "⚠ File not found: z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg"
fi
echo ""

echo "=========================================="
echo "Symlink fix completed!"
echo "=========================================="
echo ""
echo "Test URLs:"
echo "https://alumni-systems-main-mrdkxr.laravel.cloud/storage/alumni-posts/2r168mGXG0mLnK8EhrpEPItUAK40BqWp2iqa1XWU.jpg"
echo "https://alumni-systems-main-mrdkxr.laravel.cloud/storage/alumni-posts/z2vzNy1bmKDAlFeZfyHoV9IsCuGdBgf3YrwOFTF3.jpg"
echo ""

