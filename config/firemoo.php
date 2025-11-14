<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firestore API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to the Firestore-like API backend.
    |
    */

    'api_url' => env('FIRESTORE_API_URL', 'http://127.0.0.1:9090'),

    'ws_url' => env('FIRESTORE_WS_URL', 'ws://127.0.0.1:9090/websocket'),

    /*
    |--------------------------------------------------------------------------
    | Default Authentication Method
    |--------------------------------------------------------------------------
    |
    | You can use either 'api_key' or 'jwt' for authentication.
    | When using 'api_key', you need to provide both api_key and website_url.
    | When using 'jwt', you need to provide the JWT token.
    |
    */

    'default_auth_method' => env('FIRESTORE_AUTH_METHOD', 'api_key'),

    /*
    |--------------------------------------------------------------------------
    | Default API Key (Optional)
    |--------------------------------------------------------------------------
    |
    | You can set default API key and website URL here, or provide them
    | dynamically when using the service.
    |
    */

    'default_api_key' => env('FIRESTORE_API_KEY', null),
    'default_website_url' => env('FIRESTORE_WEBSITE_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | Timeout for HTTP requests in seconds.
    |
    */

    'timeout' => env('FIRESTORE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Enable or disable logging for package operations.
    | Logs will be written to storage/logs/firemoo/YYYY-MM-DD.log
    |
    */

    'logging_enabled' => env('FIRESTORE_LOGGING_ENABLED', true),

];
