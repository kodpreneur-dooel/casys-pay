<?php

return [
    'merchant_id' => env('CASYS_MERCHANT_ID', ''),
    'merchant_name' => env('CASYS_MERCHANT_NAME', ''),
    'currency' => env('CASYS_CURRENCY', 'MKD'),
    'password' => env('CASYS_PASSWORD', ''),

    'payment_url' => env('CASYS_PAYMENT_URL', 'https://vpos.cpay.com.mk/mk-MK'),

    'routes' => [
        'success' => env('CASYS_SUCCESS_URL', '/casys/success'),
        'fail' => env('CASYS_FAIL_URL', '/casys/fail'),
    ],

    'responses' => [
        'success_view' => env('CASYS_SUCCESS_VIEW', null),
        'fail_view' => env('CASYS_FAIL_VIEW', null),
    ],
];
