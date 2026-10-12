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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'graph_api_version' => env('WHATSAPP_GRAPH_API_VERSION', 'v18.0'),
        'webhook_verify_token' => env('WHATSAPP_WEBHOOK_VERIFY_TOKEN'),
    ],

    'beem' => [
        'api_key' => env('BEEM_API_KEY'),
        'secret_key' => env('BEEM_SECRET_KEY'),
        'from' => env('BEEM_WHATSAPP_FROM'),
        'broadcast_base' => env('BEEM_BROADCAST_BASE', 'https://apibroadcast.beem.africa'),
        'chat_base' => env('BEEM_CHAT_BASE', 'https://apichatcore.beem.africa'),
    ],

    'sms' => [
        'beem' => [
            'api_key' => env('BEEM_SMS_API_KEY'),
            'secret_key' => env('BEEM_SMS_SECRET_KEY'),
            'sender_id' => env('BEEM_SMS_SENDER_ID'),
            'callback_url' => env('BEEM_SMS_CALLBACK_URL'),
        ],
    ],

    'flutterwave' => [
        'public_key' => env('FLW_PUBLIC_KEY'),
        'secret_key' => env('FLW_SECRET_KEY'),
        'encryption_key' => env('FLW_ENCRYPTION_KEY'),
        'currency' => env('FLW_CURRENCY', 'TZS'),
        'environment' => env('FLW_ENV', 'production'),
    ],

];
