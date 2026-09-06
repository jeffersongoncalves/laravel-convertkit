<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ConvertKit API Key
    |--------------------------------------------------------------------------
    |
    | Used by ConvertKit's public endpoints (forms, sequences and tags:
    | list and subscribe). Find it under Settings > Advanced in your
    | ConvertKit account.
    |
    */
    'api_key' => env('CONVERTKIT_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | ConvertKit API Secret
    |--------------------------------------------------------------------------
    |
    | Required by every other endpoint (subscribers, broadcasts, removing a
    | tag from a subscriber). Find it under Settings > Advanced in your
    | ConvertKit account. Keep it out of client-side code.
    |
    */
    'api_secret' => env('CONVERTKIT_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The ConvertKit REST API v3 base URL. Override only if ConvertKit
    | changes it or you're testing against a proxy.
    |
    */
    'base_url' => env('CONVERTKIT_BASE_URL', 'https://api.convertkit.com/v3'),

];
