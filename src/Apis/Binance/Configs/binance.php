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
];
