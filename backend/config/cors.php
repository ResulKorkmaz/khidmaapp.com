<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://khidmaapp.com',
        'https://www.khidmaapp.com',
        'http://localhost:3000', // Next.js development
        'http://localhost:3001', // Next.js development (backup port)
        'http://localhost:3002', // Next.js development (backup port 2)
        'http://localhost:3003', // Next.js development (backup port 3)
        'https://*.vercel.app', // Vercel preview deployments
    ],

    'allowed_origins_patterns' => [
        '#^https://.*\.vercel\.app$#', // Vercel deployments
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [
        'X-Total-Count',
        'X-Page-Count',
        'X-Per-Page',
        'X-Current-Page',
    ],

    'max_age' => 3600,

    'supports_credentials' => true,

];
