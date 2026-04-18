<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL').'/nalog/google/povratak'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', env('APP_URL').'/nalog/facebook/povratak'),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'image_model' => env('OPENAI_IMAGE_MODEL', 'gpt-image-1.5'),
        'image_size' => env('OPENAI_IMAGE_SIZE', '1536x1024'),
        'image_quality' => env('OPENAI_IMAGE_QUALITY', 'medium'),
        'image_format' => env('OPENAI_IMAGE_FORMAT', 'webp'),
        'timeout' => (int) env('OPENAI_IMAGE_TIMEOUT', 120),
        'image_max_width' => (int) env('OPENAI_IMAGE_MAX_WIDTH', 1280),
        'image_max_height' => (int) env('OPENAI_IMAGE_MAX_HEIGHT', 854),
        'image_target_max_kb' => (int) env('OPENAI_IMAGE_TARGET_MAX_KB', 350),
        'image_optimization_quality' => (int) env('OPENAI_IMAGE_OPTIMIZATION_QUALITY', 76),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
