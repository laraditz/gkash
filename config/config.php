<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'api_version' => env('GKASH_API_VERSION', '1.5.5'),
    'merchant_id' => env('GKASH_MERCHANT_ID'),
    'signature_key' => env('GKASH_SIGNATURE_KEY'),
    'currency_code' => env('GKASH_CURRENCY_CODE', 'MYR'),
    'base_url' => 'https://api.pay.asia/api',
    'routes' => [
        'prefix' => 'gkash',
        'payment' => 'PaymentForm.aspx',
        'payment_query' => 'payment/query',
        'payment_refund' => 'payment/refund',
    ],
    'sandbox' => [
        'mode' => env('GKASH_SANDBOX_MODE', false),
        'base_url' => 'https://api-staging.pay.asia/api',
    ],
    'middleware' => ['api'],
];
