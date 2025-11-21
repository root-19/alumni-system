# Cloudinary Setup for Laravel Cloud

## Important: Environment Variables on Laravel Cloud

On Laravel Cloud, environment variables are **NOT** set in a `.env` file. They must be configured through the Laravel Cloud dashboard.

## Steps to Configure Cloudinary on Laravel Cloud:

### 1. Go to Laravel Cloud Dashboard
- Navigate to your project
- Go to **Environment Variables** or **Settings** → **Environment**

### 2. Add Cloudinary Environment Variables

Add these environment variables:

**Option 1: Individual Variables (Recommended)**
```
CLOUDINARY_CLOUD_NAME=dqotiddly
CLOUDINARY_API_KEY=519898696838735
CLOUDINARY_API_SECRET=1qAI_ytaX8PIYIjuH9O5OC5P5us
```

**Option 2: CLOUDINARY_URL Format**
```
CLOUDINARY_URL=cloudinary://519898696838735:1qAI_ytaX8PIYIjuH9O5OC5P5us@dqotiddly
```

**Option 3: Both (Most Flexible)**
Add all four variables - the system will use whichever is available.

### 3. Clear Config Cache on Server

After adding environment variables, SSH into your Laravel Cloud server and run:

```bash
php artisan config:clear
php artisan config:cache
```

Or use Laravel Cloud's console/SSH feature to run these commands.

### 4. Verify Configuration

Check the logs after uploading an image. You should see:
- `"Storing image to Cloudinary"` instead of `"Cloudinary not configured"`
- `"Image stored successfully to Cloudinary"`
- Cloudinary URLs in the logs (starting with `https://res.cloudinary.com/`)

## Debugging

If images are still going to local storage, check the logs for:
```
Cloudinary configuration check:
```

This will show which variables are set and which are missing.

## Local Development

For local development, add the same variables to your `.env` file:

```env
CLOUDINARY_CLOUD_NAME=dqotiddly
CLOUDINARY_API_KEY=519898696838735
CLOUDINARY_API_SECRET=1qAI_ytaX8PIYIjuH9O5OC5P5us
CLOUDINARY_URL=cloudinary://519898696838735:1qAI_ytaX8PIYIjuH9O5OC5P5us@dqotiddly
```

Then run:
```bash
php artisan config:clear
php artisan config:cache
```

## Troubleshooting

### Images still uploading to local storage?
1. Check Laravel Cloud dashboard - are environment variables set?
2. Run `php artisan config:clear` and `php artisan config:cache` on the server
3. Check application logs for "Cloudinary configuration check" messages
4. Verify the Cloudinary disk is configured in `config/filesystems.php`

### Getting errors when uploading?
- Check Cloudinary credentials are correct
- Verify API key and secret are valid
- Check Cloudinary dashboard for any account limits

