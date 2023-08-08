<?php

namespace Domain\Quiz\Providers;

use Illuminate\Support\ServiceProvider;

class QuizServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(QuizDeferrableServiceProvider::class);
        $this->app->register(QuizRouteServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
