<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WebController::class, 'welcome']);

Route::get('/test', [WebController::class, 'test']);

Route::get('/health', HealthCheckResultsController::class)
    ->name('health');

Route::get('/health-json', HealthCheckJsonResultsController::class)
    ->name('health_json');
