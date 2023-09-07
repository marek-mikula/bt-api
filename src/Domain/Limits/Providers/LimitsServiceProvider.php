<?php

namespace Domain\Limits\Providers;

use Illuminate\Support\ServiceProvider;

class LimitsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(LimitsDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
