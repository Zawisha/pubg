<?php
return [
    'payeer' => [
        'account' => 'P1016380562',
        'id' => '945463089',
        'key' => 'v8a9sv78aSYDdaod'
    ],
    'unitpay' => [
        'secret_key' => 'F865D839C31-869AB76115F-49421C7118',
        'cur' => 'RUB',
        'login' => 'bbklyagin@yandex.ru',
//        'login' => 'test_user',
        'projectId' => 233111,
//        'projectId' => 123,
        'domain' => 'https://unitpay.ru/api',
        'testMode' => false,
    ],
    'activePayoutSystem' => env('PAYOUT_SYSTEM', 'payeer'),
    //'activeMerchant' => env('MERCHANT', 'UNITPAY'),//'UNITPAY',
    'activeMerchant' => 'UNITPAY',//'UNITPAY',
    'merchants' => [
        'PAYEER' => [
            'driver' => 'payeer',
            'id' => '944672993',
            'key' => 'vJ5vJFj6pdxMXr1c',
            'url' => 'https://payeer.com/merchant/?'
        ],
        'ROBOKASSA' => [
            'driver' => 'robokassa',
            'id' => env('RK_LOGIN', 'Pubgbattles'),
            'url' => env('RK_URL', 'https://auth.robokassa.ru/Merchant/Index.aspx?'), //isTest=1&
            'apiUrl' => env('RK_API_URL', ''),
            'secret1' => env('RK_SECRET_1', 'l7O7f9UOjNZdE9Tdq0Ue'),
            'secret2' => env('RK_SECRET_2', 'it3da8kaBQfN3JuH3Hc9')
        ],
        'UNITPAY' => [
            'driver' => 'unitpay',
            'public_key' => '279011-de906',
            'secret_key' => 'd693c4fc697a708a6175799bed69d46a',
            'url' => 'https://unitpay.ru/pay/',
            'desc' => 'UNITOURN BALANCE',
            'cur' => 'USD'
        ]
    ]

];