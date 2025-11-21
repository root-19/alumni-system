#!/bin/bash

# Laravel Deployment Script
# Run this after deploying to clear caches and setup storage

echo "Setting up Laravel deployment..."

# Create storage directories if they don't exist
echo "Creating storage directories..."
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Set proper permissions
echo "Setting storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Remove existing symlink if it exists (broken or incorrect)
echo "Removing existing storage symlink if exists..."
rm -f public/storage
rm -rf public/storage  # Also remove if it's a directory

# Ensure storage directories exist
echo "Ensuring storage directories exist..."
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts

# Create storage symlink (important for images!)
echo "Creating storage symlink..."
php artisan storage:link || {
    echo "Artisan command failed, trying manual symlink..."
    cd public && ln -s ../storage/app/public storage && cd ..
}

# Verify symlink exists and works
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink -f public/storage 2>/dev/null || readlink public/storage)
    echo "✓ Storage symlink verified successfully"
    echo "  Link: public/storage -> $LINK_TARGET"
    
    # Test if symlink resolves
    if [ ! -d "public/storage" ]; then
        echo "⚠ WARNING: Symlink exists but does not resolve!"
        echo "  Recreating..."
        rm -f public/storage
        php artisan storage:link || (cd public && ln -s ../storage/app/public storage && cd ..)
    fi
else
    echo "❌ ERROR: Storage symlink was not created!"
    echo "  Attempting manual creation..."
    cd public && ln -s ../storage/app/public storage && cd ..
    if [ -L "public/storage" ]; then
        echo "✓ Manual symlink creation successful"
    else
        echo "❌ Manual creation failed - please run 'php artisan storage:link' manually"
    fi
fi

# Clear caches
echo "Clearing caches..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear

# Set permissions again after symlink creation
echo "Setting final permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Deployment setup completed successfully!"

