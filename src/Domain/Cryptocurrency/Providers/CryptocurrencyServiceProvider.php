<?php

namespace Domain\Cryptocurrency\Providers;

use Illuminate\Support\ServiceProvider;

class CryptocurrencyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CryptocurrencyDeferredServiceProvider::class);
        $this->app->register(CryptocurrencyRouteServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
