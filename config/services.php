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
 
    'oto' => [
        'base_url' => env('OTO_API_URL', 'https://api.tryoto.com'),
        'api_key' => env('OTO_API_KEY'),
     //   'company_code' => env('OTO_COMPANY_CODE'),
        'brand_id' => env('OTO_BRAND_ID'),
        'sender_name' => env('OTO_SENDER_NAME', 'متجر برنتس'),
        'sender_phone' => env('OTO_SENDER_PHONE'),
        'sender_email' => env('OTO_SENDER_EMAIL'),
        'sender_city' => env('OTO_SENDER_CITY', 'Riyadh'),
        'sender_district' => env('OTO_SENDER_DISTRICT', 'Al Sulimaniyah'),
        'sender_address' => env('OTO_SENDER_ADDRESS'),
        'sender_postal_code' => env('OTO_SENDER_POSTAL_CODE'),
        'order_prefix' => env('OTO_ORDER_PREFIX', 'OTO'),
        'timeout' => env('OTO_TIMEOUT', 60),
            'token' => env('OTO_API_KEY'),
        'cache_ttl' => env('OTO_CACHE_TTL', 1440), // 24 ساعة بالدقائق
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paymob' => [
        'api_key' => env('PAYMOB_API_KEY', 'sau_sk_test_41fe73bfd7c6fb2a3ce192de1ad1f28b10824122206a84874ee0d9bd6be96698'),
        'hmac_secret' => env('PAYMOB_HMAC_SECRET', 'C37FF7E038FCEBC1C13F7ECBFBAF71EE'),
        'integration_id' => env('PAYMOB_INTEGRATION_ID',  18155),
        'iframe_id' => env('PAYMOB_IFRAME_ID', '11348'),
        'username' => env('PAYMOB_USERNAME', '501556342'),
        'public_key' => env('PAYMOB_PUBLIC_KEY', 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TVRNd09UUXNJbTVoYldVaU9pSnBibWwwYVdGc0luMC5lamwwLTNBaWM3b3BZWjNhV3FqTmU4RjU2OUVPaU9NNUhqRjlTaThxVmRJSkN3dU1fN21UTmV1ZUpzbUdqMlpBajg3NkJyMEtGTlJLb0NwM21iMnp2UQ=='),
        'password' => env('PAYMOB_PASSWORD', 'Almlm13212..@'),
        'mode'            => env('PAYMOB_MODE', 'test'),
    ],
    //    'paymob' => [
    //     'username' => env('PAYMOB_USERNAME'),
    //     'password' => env('PAYMOB_PASSWORD'),
    //     'api_key' => env('PAYMOB_API_KEY'),
    //     'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
    //     'integration_id' => env('PAYMOB_INTEGRATION_ID', '19293'),
    //     'iframe_id' => env('PAYMOB_IFRAME_ID', '11784'),
    //     'mode' => env('PAYMOB_MODE', 'test'),        // test أو live
    //     'currency' => env('PAYMOB_CURRENCY', 'SAR'),
    //     'base_url' => env('PAYMOB_BASE_URL', 'https://ksa.paymob.com'),

    //     // Endpoints
    //     'auth_url' => env('PAYMOB_BASE_URL', 'https://ksa.paymob.com').'/api/auth/tokens',
    //     'payment_links_url' => env('PAYMOB_BASE_URL', 'https://ksa.paymob.com').'/api/ecommerce/payment-links',

    //     // Public key
    //     'public_key' => env('PAYMOB_PUBLIC_KEY'),

    //     // Features
    //     'tokenization_enabled' => env('PAYMOB_TOKENIZATION_ENABLED', false),
    //     'save_card_enabled' => env('PAYMOB_SAVE_CARD_ENABLED', true),

    //     // IP Whitelist
    //     'allowed_ips' => explode(',', env('PAYMOB_ALLOWED_IPS', '')),

    //     // URLs
    //     'callback_url' => env('PAYMOB_CALLBACK_URL'),
    //     'return_url' => env('PAYMOB_RETURN_URL'),
    //     'cancel_url' => env('PAYMOB_CANCEL_URL'),
    // ],
    'tamara' => [
        'sandbox' => env('TAMARA_SANDBOX', true),
        'username' => env('TAMARA_USERNAME'),
        'password' => env('TAMARA_PASSWORD'),
        'api_key' => env('TAMARA_API_KEY'),
        'notification_token' => env('TAMARA_NOTIFICATION_TOKEN'),
        'webhook_token' => env('TAMARA_WEBHOOK_TOKEN'),
        'api_token' => env('TAMARA_API_TOKEN'),
        'currency' => 'SAR',
    ],
    'tabby' => [
        // بيئة Tabby (Sandbox / Production)
        'sandbox' => env('TABBY_SANDBOX', true),

        // Merchant Info
        'merchant_code' => env('TABBY_MERCHANT_CODE'),

        // Keys
        'secret_key' => env('TABBY_SECRET_KEY'),
        'public_key' => env('TABBY_PUBLIC_KEY'),

        // Webhook
        'webhook_secret' => env('TABBY_WEBHOOK_SECRET'),

        'currency' => 'SAR',
        'base_url' => env('TABBY_SANDBOX', true)
            ? 'https://api.tabby.ai/api/v1/sandbox'
            : 'https://api.tabby.ai/api/v1',
    ],

];
