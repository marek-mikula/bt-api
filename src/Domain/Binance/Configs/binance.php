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
    'url' => env('BINANCE_URL'),
    'testnet' => [
        'enabled' => env('BINANCE_TESTNET_ENABLED', false),
        'url' => env('BINANCE_TESTNET_URL', ''),
    ],
];
