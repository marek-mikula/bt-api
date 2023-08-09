<?php

/*
|--------------------------------------------------------------------------
| Coinmarketcap API configuration
|--------------------------------------------------------------------------
|
| This file is for storing the credentials and other information about
| Coinmarketcap API. If mock option is ON, the client gets mocked and the
| results are taken from JSON files from ~/Resources/mocks.
|
*/

return [
    'mock' => env('COINMARKETCAP_MOCK', false),
    'url' => env('COINMARKETCAP_URL'),
    'key' => env('COINMARKETCAP_KEY'),
];
