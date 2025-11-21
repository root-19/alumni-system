#!/bin/bash

# Laravel File Permissions Fix Script
# Run this script to fix file permissions for storage and cache directories

echo "=========================================="
echo "Laravel File Permissions Fix Script"
echo "=========================================="
echo ""

# Get the current user (web server user)
WEB_USER=${WEB_USER:-www-data}
echo "Using web server user: $WEB_USER"
echo ""

# Set ownership
echo "Setting ownership..."
sudo chown -R $WEB_USER:$WEB_USER .
echo "✓ Ownership set"
echo ""

# Set directory permissions (755 for directories)
echo "Setting directory permissions (755)..."
find . -type d -exec chmod 755 {} \;
echo "✓ Directory permissions set"
echo ""

# Set file permissions (644 for files)
echo "Setting file permissions (644)..."
find . -type f -exec chmod 644 {} \;
echo "✓ File permissions set"
echo ""

# Special permissions for storage and cache
echo "Setting special permissions for storage and cache..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✓ Storage and cache permissions set"
echo ""

# Make sure storage subdirectories exist and have correct permissions
echo "Creating storage subdirectories if they don't exist..."
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
chmod -R 775 storage/app/public
echo "✓ Storage subdirectories created"
echo ""

# Remove existing symlink if it exists (broken or incorrect)
echo "Removing existing storage symlink if exists..."
rm -f public/storage
rm -rf public/storage  # Also remove if it's a directory (wrong setup)
echo "✓ Old symlink/directory removed"
echo ""

# Ensure storage directories exist
echo "Creating storage directories..."
mkdir -p storage/app/public/news_images
mkdir -p storage/app/public/alumni-posts
echo "✓ Storage directories created"
echo ""

# Recreate storage symlink
echo "Recreating storage symlink..."
php artisan storage:link
echo "✓ Storage symlink command executed"
echo ""

# Verify symlink exists and is correct
if [ -L "public/storage" ]; then
    LINK_TARGET=$(readlink -f public/storage)
    EXPECTED_TARGET=$(readlink -f storage/app/public)
    
    if [ "$LINK_TARGET" = "$EXPECTED_TARGET" ]; then
        echo "✓ Storage symlink verified successfully"
        echo "  Link: public/storage -> $LINK_TARGET"
    else
        echo "⚠ WARNING: Symlink exists but points to wrong location!"
        echo "  Current: $LINK_TARGET"
        echo "  Expected: $EXPECTED_TARGET"
        echo "  Removing and recreating..."
        rm -f public/storage
        php artisan storage:link
    fi
elif [ -d "public/storage" ]; then
    echo "⚠ WARNING: public/storage is a directory, not a symlink!"
    echo "  Removing directory and creating symlink..."
    rm -rf public/storage
    php artisan storage:link
else
    echo "❌ ERROR: Storage symlink was not created!"
    echo "  Attempting manual creation..."
    ln -s ../storage/app/public public/storage
    if [ -L "public/storage" ]; then
        echo "✓ Manual symlink creation successful"
    else
        echo "❌ Manual symlink creation failed!"
        echo "  Please run 'php artisan storage:link' manually"
    fi
fi
echo ""

# Clear all caches
echo "Clearing all caches..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan optimize:clear
echo "✓ All caches cleared"
echo ""

echo "=========================================="
echo "Permissions fix completed successfully!"
echo "=========================================="

