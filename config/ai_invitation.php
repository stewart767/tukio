<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Vision AI & OCR Provider Configuration
    |--------------------------------------------------------------------------
    |
    | Supported providers: 'gemini', 'openai', 'heuristic'
    | If an API key is not configured or an external request fails,
    | the system automatically falls back to the robust local heuristic engine.
    |
    */
    'provider' => env('AI_VISION_PROVIDER', 'gemini'),

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_VISION_MODEL', 'gemini-1.5-flash'),
        'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models',
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_VISION_MODEL', 'gpt-4o-mini'),
        'endpoint' => 'https://api.openai.com/v1/chat/completions',
    ],

    'timeout' => env('AI_VISION_TIMEOUT', 25),

    /*
    |--------------------------------------------------------------------------
    | QR Code Placement Guidelines
    |--------------------------------------------------------------------------
    |
    | Normalized dimensions (0.0 to 1.0) and safety thresholds for QR placement.
    |
    */
    'qr' => [
        'recommended_size' => 0.16, // 16% of width
        'min_size' => 0.12,          // 12% of width (minimum scannable size threshold)
        'quiet_zone_padding' => 0.015, // 1.5% padding around QR
        'preferred_positions' => [
            'bottom_right' => ['x' => 0.76, 'y' => 0.77],
            'bottom_left'  => ['x' => 0.08, 'y' => 0.77],
            'bottom_center'=> ['x' => 0.42, 'y' => 0.77],
            'top_right'    => ['x' => 0.76, 'y' => 0.06],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Restrictions
    |--------------------------------------------------------------------------
    */
    'upload' => [
        'max_file_size_kb' => 15360, // 15MB
        'min_dimension' => 500,     // pixels
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp'],
    ],
];
