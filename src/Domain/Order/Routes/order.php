<?php

use Domain\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], static function (): void {
    Route::post('/', [OrderController::class, 'place'])
        ->name('place');
});
