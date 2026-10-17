<?php

return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie'
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://72.62.25.136:3000',
        'https://www.talaaljazeera.com',
        'https://ecommerce-xo.vercel.app',
        'https://talaaljazeera.com',
        'https://www.talaaljazeera.com',
        'https://www.flashy-card.com',
        'https://flashy-card.com',


    ],

    'allowed_origins_patterns' => [
        '#^https://.*\.vercel\.app$#',
        '#^http://(localhost|127\.0\.0\.1|192\.168\.\d+\.\d+)(:\d+)?$#'
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
