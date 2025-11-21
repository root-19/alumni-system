#!/bin/bash

# Laravel Deployment Script
# Run this after deploying to clear caches

echo "Clearing Laravel caches..."

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Caches cleared successfully!"

