<?php

use Domain\Currency\Http\Controllers\CryptocurrencyController;
use Domain\Currency\Http\Controllers\PairController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::group(['prefix' => '/cryptocurrencies', 'as' => 'cryptocurrencies.'], static function (): void {
        Route::get('/', [CryptocurrencyController::class, 'index'])
            ->name('index');

        Route::get('/{cryptocurrency}', [CryptocurrencyController::class, 'show'])
            ->whereNumber('cryptocurrency')
            ->name('show');

        Route::get('/{cryptocurrency}/quote', [CryptocurrencyController::class, 'quote'])
            ->whereNumber('cryptocurrency')
            ->name('quote');

        Route::get('/{cryptocurrency}/trade', [CryptocurrencyController::class, 'trade'])
            ->whereNumber('cryptocurrency')
            ->name('trade');
    });

    Route::group(['prefix' => '/pairs', 'as' => 'pairs.'], static function (): void {
        Route::get('/{pair}/price', [PairController::class, 'price'])
            ->where('pair', '[A-Z]+')
            ->name('price');
    });
});
