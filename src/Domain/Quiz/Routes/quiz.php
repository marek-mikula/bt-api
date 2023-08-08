<?php

use Domain\Quiz\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum', 'quiz']], static function (): void {
    Route::get('/questions', [QuizController::class, 'questions'])
        ->name('questions');

    Route::post('/finish', [QuizController::class, 'finish'])
        ->name('finish');
});
