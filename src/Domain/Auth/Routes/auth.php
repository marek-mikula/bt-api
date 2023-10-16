<?php

use Domain\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
    ->name('csrf_cookie');

Route::group(['middleware' => ['guest:sanctum']], static function (): void {
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');
});

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/me', [AuthController::class, 'me'])
        ->name('me');
});
