<?php

namespace Domain\CoinMarketCap\Providers;

use Illuminate\Support\ServiceProvider;

class CoinMarketCapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CoinMarketCapDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
