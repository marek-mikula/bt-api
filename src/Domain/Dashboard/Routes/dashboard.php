<?php

use Domain\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::get('/', [DashboardController::class, 'index']);
});
