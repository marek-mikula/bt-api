<?php

namespace Domain\CoinMarketCap\Providers;

use Illuminate\Support\ServiceProvider;

class CoinMarketCapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(CoinMarketCapDeferrableServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
