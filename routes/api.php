<?php

use App\Enums\MfaTokenTypeEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MfaController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Middleware\Mfa\MfaTokenMiddleware;
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
    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::group(['middleware' => 'auth:api'], function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        Route::get('/refresh', [AuthController::class, 'refresh'])
            ->name('refresh');

        Route::get('/me', [AuthController::class, 'me'])
            ->name('me');
    });
});

Route::group(['prefix' => '/mfa', 'as' => 'mfa.'], function (): void {
    Route::post('/verify-email', [MfaController::class, 'verifyEmail'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::VERIFY_EMAIL))
        ->name('verify-email');

    Route::post('/verify-device', [MfaController::class, 'verifyDevice'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::VERIFY_DEVICE))
        ->name('verify-device');

    Route::post('/reset-password', [MfaController::class, 'resetPassword'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::RESET_PASSWORD))
        ->name('reset-password');
});

Route::group(['prefix' => '/password-reset', 'as' => 'password-reset.'], function (): void {
    Route::post('/send-email', [PasswordResetController::class, 'sendEmail'])
        ->name('send-email');
});
