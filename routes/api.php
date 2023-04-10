<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => '/auth', 'as' => 'auth.'], function (): void {
    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::group(['middleware' => 'auth:api'], function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        Route::get('/refresh', [AuthController::class, 'refresh'])
            ->name('refresh');

        Route::get('/me', [AuthController::class, 'me'])
            ->name('me');
    });
});
