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

# Recreate storage symlink
echo "Recreating storage symlink..."
php artisan storage:link
echo "✓ Storage symlink recreated"
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

