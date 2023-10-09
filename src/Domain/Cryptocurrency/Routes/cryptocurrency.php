<?php

use Domain\Cryptocurrency\Http\Controllers\CryptocurrencyController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::get('/', [CryptocurrencyController::class, 'index'])
        ->name('index');

    Route::get('/{cryptocurrency}', [CryptocurrencyController::class, 'show'])
        ->whereNumber('cryptocurrency')
        ->name('show');

    Route::get('/{cryptocurrency}/quote', [CryptocurrencyController::class, 'quote'])
        ->whereNumber('cryptocurrency')
        ->name('quote');
});
