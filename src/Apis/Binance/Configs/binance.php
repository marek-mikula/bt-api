<?php

/*
|--------------------------------------------------------------------------
| Binance API configuration
|--------------------------------------------------------------------------
|
| This file is for storing the credentials and other information about
| Binance API. If testnet is enabled, the endpoints for SPOT trading only
| will use fake credit and data.
|
*/

return [
    'mock' => env('BINANCE_MOCK', false),
    'limiter' => env('BINANCE_LIMITER', true),
    'url' => env('BINANCE_URL'),
    'keys' => [
        'public' => env('BINANCE_PUBLIC_KEY'),
        'secret' => env('BINANCE_SECRET_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Binance SPOT testnet configuration
    |--------------------------------------------------------------------------
    |
    | Binance provides users with a testing network for SPOT trading. These
    | values are used as a replacement for values above when the testnet is
    | enabled. These values are replaced only when calling /api endpoints!
    |
    | More info here: https://testnet.binance.vision/.
    |
    */

    'testnet' => [
        'enabled' => env('BINANCE_TESTNET_ENABLED', false),
        'url' => env('BINANCE_TESTNET_URL'),
        'keys' => [
            'public' => env('BINANCE_TESTNET_PUBLIC_KEY'),
            'secret' => env('BINANCE_TESTNET_SECRET_KEY'),
        ],
    ],
];
