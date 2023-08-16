<?php

namespace Domain\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(AuthDeferredServiceProvider::class);
        $this->app->register(AuthRouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'auth');
    }
}
