<?php

use Domain\Auth\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:sanctum'], static function (): void {
    Route::post('/send-email', [PasswordResetController::class, 'sendEmail'])
        ->name('send_email');
});
