#!/bin/bash

# Laravel Deployment Script
# Run this after deploying to clear caches and setup storage

echo "Setting up Laravel deployment..."

# Clear caches
echo "Clearing caches..."
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Create storage symlink (important for images!)
echo "Creating storage symlink..."
php artisan storage:link

# Set proper permissions (if needed)
echo "Setting storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Deployment setup completed successfully!"

