<?php

namespace Domain\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(DashboardDeferredServiceProvider::class);
        $this->app->register(DashboardRouteServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
