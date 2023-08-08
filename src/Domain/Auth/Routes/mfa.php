<?php

use Domain\Auth\Enums\MfaTokenTypeEnum;
use Domain\Auth\Http\Controllers\MfaController;
use Domain\Auth\Http\Middleware\MfaTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:sanctum'], static function (): void {
    Route::post('/verify-email', [MfaController::class, 'verifyEmail'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::VERIFY_EMAIL))
        ->name('verify-email');

    Route::post('/reset-password', [MfaController::class, 'resetPassword'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::RESET_PASSWORD))
        ->name('reset-password');
});
