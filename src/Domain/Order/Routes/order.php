<?php

use Domain\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], static function (): void {
    Route::post('/buy', [OrderController::class, 'buy'])
        ->name('buy');

    Route::post('/sell', [OrderController::class, 'sell'])
        ->name('sell');
});
