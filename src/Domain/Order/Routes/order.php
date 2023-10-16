<?php

use Domain\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::get('/', [OrderController::class, 'index'])
        ->name('index');

    Route::post('/', [OrderController::class, 'create'])
        ->name('create');
});
