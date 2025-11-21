# Clear View Cache - Laravel Cloud

## Problem:
Syntax error sa compiled view: `unexpected token "endif"`

## Solution:
Clear ang view cache sa Laravel Cloud server.

## Commands to Run (SSH into Laravel Cloud):

```bash
# Clear view cache
php artisan view:clear

# Clear all caches (recommended)
php artisan optimize:clear

# Or individually:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## After Clearing Cache:

The file structure is correct - all `@if/@endif`, `@foreach/@endforeach`, `@php/@endphp` pairs are properly matched.

The error is likely from a **stale compiled view cache** that has old code.

## Quick Fix:

```bash
# One command to clear everything
php artisan optimize:clear
```

This will clear:
- Config cache
- Route cache  
- View cache
- Application cache

After running this, refresh the page and the error should be gone.

