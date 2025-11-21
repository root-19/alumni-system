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

# Create storage symlink (important for images!)
echo "Creating storage symlink..."
php artisan storage:link || true

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

