<?php

use Domain\User\Http\Controllers\UserAccountSettingsController;
use Domain\User\Http\Controllers\UserNotificationController;
use Domain\User\Http\Controllers\UserSettingsController;
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

    Route::group(['prefix' => '/settings', 'as' => 'settings.'], static function (): void {
        Route::group(['prefix' => '/account', 'as' => 'account.'], static function (): void {
            Route::post('/save-personal', [UserAccountSettingsController::class, 'savePersonal'])
                ->name('save_personal');

            Route::post('/save-password', [UserAccountSettingsController::class, 'savePassword'])
                ->name('save_password');

            Route::post('/save-keys', [UserAccountSettingsController::class, 'saveKeys'])
                ->name('save_keys');
        });

        Route::post('/save-notifications', [UserSettingsController::class, 'saveNotifications'])
            ->name('save_notifications');

        Route::post('/save-limits', [UserSettingsController::class, 'saveLimits'])
            ->name('save_limits');
    });
});
