<?php

use Domain\WhaleAlert\Http\WhaleAlertController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], static function (): void {
    Route::get('/', [WhaleAlertController::class, 'index'])->name('index');
});
