<?php

use App\Enums\MfaTokenTypeEnum;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MfaController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Middleware\MfaTokenMiddleware;
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

    Route::get('/csrf-cookie', [AuthController::class, 'csrfCookie'])
        ->name('csrf-cookie');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function (): void {
        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        Route::get('/me', [AuthController::class, 'me'])
            ->name('me');
    });
});

Route::group(['prefix' => '/mfa', 'as' => 'mfa.'], function (): void {
    Route::post('/verify-email', [MfaController::class, 'verifyEmail'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::VERIFY_EMAIL))
        ->name('verify-email');

    Route::post('/reset-password', [MfaController::class, 'resetPassword'])
        ->middleware(MfaTokenMiddleware::apply(MfaTokenTypeEnum::RESET_PASSWORD))
        ->name('reset-password');
});

Route::group(['prefix' => '/password-reset', 'as' => 'password-reset.'], function (): void {
    Route::post('/send-email', [PasswordResetController::class, 'sendEmail'])
        ->name('send-email');
});

Route::group(['prefix' => '/quiz', 'as' => 'quiz.', 'middleware' => ['auth:sanctum', 'quiz']], function (): void {
    Route::get('/questions', [QuizController::class, 'questions'])
        ->name('questions');

    Route::post('/finish', [QuizController::class, 'finish'])
        ->name('finish');
});

Route::group(['prefix' => '/user', 'as' => 'user.', 'middleware' => ['auth:sanctum']], function (): void {
    Route::group(['prefix' => '/notifications', 'as' => 'notifications.'], function (): void {
        Route::get('/', [UserNotificationController::class, 'index'])
            ->name('index');

        Route::get('/unread', [UserNotificationController::class, 'unread'])
            ->name('unread');

        Route::post('/mark-as-read', [UserNotificationController::class, 'markAsRead'])
            ->name('mark-as-read');
    });
});
