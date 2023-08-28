<?php

use Domain\Cryptocurrency\Http\Controllers\CryptocurrencyController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::get('/', [CryptocurrencyController::class, 'index'])
        ->name('index');
});
