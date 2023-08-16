<?php

use Domain\Search\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'search'])
    ->name('search');
