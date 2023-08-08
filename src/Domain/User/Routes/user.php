<?php

use Domain\User\Http\Controllers\UserNotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::group(['prefix' => '/notifications', 'as' => 'notifications.'], static function (): void {
        Route::get('/', [UserNotificationController::class, 'index'])
            ->name('index');

        Route::get('/unread', [UserNotificationController::class, 'unread'])
            ->name('unread');

        Route::post('/mark-as-read', [UserNotificationController::class, 'markAsRead'])
            ->name('mark_as_read');
    });
});
