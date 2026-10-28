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
 
    // 'oto' => [
    //     'base_url' => env('OTO_API_URL', 'https://api.tryoto.com'),
    //     'api_key' => env('OTO_API_KEY'),
    //  //   'company_code' => env('OTO_COMPANY_CODE'),
    //     'brand_id' => env('OTO_BRAND_ID'),
    //     'sender_name' => env('OTO_SENDER_NAME', 'متجر برنتس'),
    //     'sender_phone' => env('OTO_SENDER_PHONE'),
    //     'sender_email' => env('OTO_SENDER_EMAIL'),
    //     'sender_city' => env('OTO_SENDER_CITY', 'Riyadh'),
    //     'sender_district' => env('OTO_SENDER_DISTRICT', 'Al Sulimaniyah'),
    //     'sender_address' => env('OTO_SENDER_ADDRESS'),
    //     'sender_postal_code' => env('OTO_SENDER_POSTAL_CODE'),
    //     'order_prefix' => env('OTO_ORDER_PREFIX', 'OTO'),
    //     'timeout' => env('OTO_TIMEOUT', 60),
    //         'token' => env('OTO_API_KEY'),
    //     'cache_ttl' => env('OTO_CACHE_TTL', 1440), // 24 ساعة بالدقائق
    // ],
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
        // كل القيم بتيجي من .env — مفيش مفاتيح مكتوبة في الكود.
        'api_key' => env('PAYMOB_API_KEY'),
        'secret_key' => env('PAYMOB_SECRET_KEY'),
        'public_key' => env('PAYMOB_PUBLIC_KEY'),
        'hmac_secret' => env('PAYMOB_HMAC_SECRET'),
        'integration_id' => env('PAYMOB_INTEGRATION_ID'),
        // ممكن أكتر من تكامل (كارت + Apple Pay) مفصولين بفاصلة.
        'integration_ids' => array_values(array_filter(array_map(
            'intval',
            explode(',', (string) env('PAYMOB_INTEGRATION_IDS', env('PAYMOB_INTEGRATION_ID', '')))
        ))),
        // تكامل Apple Pay وحده (لما العميل يختار Apple Pay من صفحة الدفع).
        'apple_pay_integration_id' => (int) env('PAYMOB_APPLE_PAY_INTEGRATION_ID', 35561),
        'iframe_id' => env('PAYMOB_IFRAME_ID'),
        'username' => env('PAYMOB_USERNAME'),
        'password' => env('PAYMOB_PASSWORD'),
        'callback_url' => env('PAYMOB_CALLBACK_URL'),
        'mode' => env('PAYMOB_MODE', 'test'),
        'frontend_url' => env('PAYMOB_FRONTEND_URL', 'https://talaaljazeera.com'),
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
        'sandbox' => env('TAMARA_SANDBOX', false),
        'username' => env('TAMARA_USERNAME','a7madmabrouk701@gmail.com'),
        'password' => env('TAMARA_PASSWORD','2uen8sCwuNn$!9D'),
        'api_key' => env('TAMARA_API_KEY','aa852b8e-b3cf-4930-9b49-d412dee666fa'),
        'notification_token' => env('TAMARA_NOTIFICATION_TOKEN','e495b422-a6a9-419a-ad2c-b9f58832bafc'),
        'webhook_token' => env('TAMARA_WEBHOOK_TOKEN',''),
        'api_token' => env('TAMARA_API_TOKEN','eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI1NDQxODRjMC1mMWI0LTQzZDYtOTAzOS1iZGE3ZjI4MjhlNzkiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiNDVhZTA2OTAtNzM0My00Y2VhLTk5ZTItMzNkN2FhODgzYmRiIiwicm9sZXMiOlsiUk9MRV9NRVJDSEFOVCJdLCJpc010bHMiOmZhbHNlLCJpYXQiOjE3NjkxOTIzMTQsImlzcyI6IlRhbWFyYSBQUCJ9.nSiuY_lv3gEGUNWlPCpYWxAZyc-GYaeJsO2M2dQilvNPVQhwWjDzp1KbVXNjW3q836Sa-o7dghGqitGV6ni-X-bRDnqNSEuN6YKrOh4qEiMLV8OGHEWMnsxuhIRbMjmmjOq15sD7lxDHe05TbxDBJIakW9oVVA9I5yN71JqMzp-uA_m0uZnh6kQw_QTGFGhi4CF-hkzPgn5LfHr5bqklZ5iP2riKQtbWVukKxYnuXAdREY5NeUx2rlDYVbAPq8fJo6s1oxLDZaffx_roUzQNto28iCWkD61L-1UyXu0To015G16_4VvS65gQ7miD0ApZvsgqUdajb773IKQaEbOKgw'),
        'currency' => 'SAR',
         'merchant_code' => env('TAMARA_MERCHANT_CODE', 'TAL'),
    ],
    'tabby' => [
        // بيئة Tabby (Sandbox / Production)
        'sandbox' => env('TABBY_SANDBOX', true),

        // Merchant Info
        'merchant_code' => env('TABBY_MERCHANT_CODE',  'TAL'),

        // Keys
        'secret_key' => env('TABBY_SECRET_KEY','sk_test_019b4606-613e-e6be-c627-296d96beb3de'),
        'public_key' => env('TABBY_PUBLIC_KEY','pk_test_019b4606-613e-e6be-c627-296cfc69ea92'),

        // Webhook
        'webhook_secret' => env('TABBY_WEBHOOK_SECRET'),

        'currency' => 'SAR',
        'base_url' => env('TABBY_SANDBOX', true)
            ? 'https://api.tabby.ai/api/v1/sandbox'
            : 'https://api.tabby.ai/api/v1',
    ],
    'whatsapp_cloud' => [
        'access_token' => env('WHATSAPP_CLOUD_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_CLOUD_PHONE_NUMBER_ID', '1457396494115488'),
        'api_version' => env('WHATSAPP_CLOUD_API_VERSION', 'v25.0'),
        'otp_template' => env('WHATSAPP_CLOUD_OTP_TEMPLATE', 'waitmeyah_otp'),
        'otp_language' => env('WHATSAPP_CLOUD_OTP_LANGUAGE', 'ar'),
        'waba_id' => env('WHATSAPP_CLOUD_WABA_ID'),
        // Webhook (Meta → nosso servidor)
        'webhook_verify_token' => env('WHATSAPP_CLOUD_WEBHOOK_VERIFY_TOKEN'),
        'app_secret' => env('WHATSAPP_CLOUD_APP_SECRET'),
    ],
    'oto' => [
    'api_url' => env('OTO_API_URL', 'https://api.tryoto.com/rest/v2/'),
    // لازم يتحدد من الـ .env — مفيش توكن مكتوب في الكود.
    'api_token' => env('OTO_API_KEY'),
    'sender_address' => env('OTO_SENDER_ADDRESS'),
    'sender_city' => env('OTO_SENDER_CITY', 'Riyadh'),
    'sender_phone' => env('OTO_SENDER_PHONE'),
    'sender_email' => env('OTO_SENDER_EMAIL'),
    'default_item_weight' => (float) env('OTO_DEFAULT_ITEM_WEIGHT', 0.5),
],

];
