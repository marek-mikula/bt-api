<?php

/*
|--------------------------------------------------------------------------
| Cryptopanic API configuration
|--------------------------------------------------------------------------
|
| This file is for storing the credentials and other information about
| Cryptopanic API. If mock option is ON, the client gets mocked and the
| results are taken from JSON files from ~/Resources/mocks.
|
*/

return [
    'mock' => env('CRYPTOPANIC_MOCK', false),
    'url' => env('CRYPTOPANIC_URL'),
    'key' => env('CRYPTOPANIC_KEY'),
];
