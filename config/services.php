<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'simplecert' => [
        'api_key' => env('SIMPLECERT_API_KEY', 'q7aipClj6xiUXmE2OlylttEwvcI1wPJ9Hoi6GdRKhVVs93GYDOwJ8NcvUEfQ'),
        'email' => env('SIMPLECERT_EMAIL'),
        'password' => env('SIMPLECERT_PASSWORD'),
        'base_url' => env('SIMPLECERT_BASE_URL', 'https://app.simplecert.net/api'),
    ],

];
