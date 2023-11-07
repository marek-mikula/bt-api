<?php

use Domain\User\Http\Controllers\UserAccountSettingsController;
use Domain\User\Http\Controllers\UserAlertsSettingsController;
use Domain\User\Http\Controllers\UserAssetsController;
use Domain\User\Http\Controllers\UserController;
use Domain\User\Http\Controllers\UserLimitsSettingsController;
use Domain\User\Http\Controllers\UserNotificationController;
use Domain\User\Http\Controllers\UserNotificationsSettingsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::delete('/', [UserController::class, 'delete'])
        ->name('delete');

    Route::group(['prefix' => '/notifications', 'as' => 'notifications.'], static function (): void {
        Route::get('/', [UserNotificationController::class, 'index'])
            ->name('index');

        Route::get('/unread', [UserNotificationController::class, 'unread'])
            ->name('unread');

        Route::post('/mark-as-read', [UserNotificationController::class, 'markAsRead'])
            ->name('mark_as_read');
    });

    Route::group(['prefix' => '/assets', 'as' => 'assets.'], static function (): void {
        Route::get('/', [UserAssetsController::class, 'index'])
            ->name('index');
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

        Route::group(['prefix' => '/alerts', 'as' => 'alerts.'], static function (): void {
            Route::get('/', [UserAlertsSettingsController::class, 'index'])
                ->name('index');

            Route::post('/', [UserAlertsSettingsController::class, 'store'])
                ->name('store');

            Route::delete('/{alert}', [UserAlertsSettingsController::class, 'delete'])
                ->whereNumber('alert')
                ->name('delete');
        });

        Route::group(['prefix' => '/limits', 'as' => 'limits.'], static function (): void {
            Route::get('/', [UserLimitsSettingsController::class, 'show'])
                ->name('show');

            Route::patch('/', [UserLimitsSettingsController::class, 'update'])
                ->name('update');
        });

        Route::group(['prefix' => '/notifications', 'as' => 'notifications.'], static function (): void {
            Route::patch('/', [UserNotificationsSettingsController::class, 'update'])
                ->name('update');
        });
    });
});
