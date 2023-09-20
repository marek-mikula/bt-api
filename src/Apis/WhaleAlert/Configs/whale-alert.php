<?php

/*
|--------------------------------------------------------------------------
| WhaleAlert API configuration
|--------------------------------------------------------------------------
|
| This file is for storing the credentials and other information about
| WhaleAlert API. If mock option is ON, the client gets mocked and the
| results are taken from JSON files from ~/Resources/mocks.
|
*/

return [
    'mock' => env('WHALE_ALERT_MOCK', false),
    'url' => env('WHALE_ALERT_URL'),
    'key' => env('WHALE_ALERT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | List of supported currencies
    |--------------------------------------------------------------------------
    |
    | The list of currencies that are supported by this application. For other
    | currencies no alerts or notifications will be triggered.
    |
    */

    'supported_currencies' => [
        'btc', // Bitcoin
        'eth', // Ethereum
        'xrp', // Ripple
        'bnb', // Binance coin
    ],
];
