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
];
