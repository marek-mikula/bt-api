<?php

namespace Apis\Coinmarketcap\Providers;

use Illuminate\Support\ServiceProvider;

class CoinmarketcapApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CoinmarketcapApiDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Configs/coinmarketcap.php', 'coinmarketcap');
    }

    public function boot(): void
    {
        //
    }
}
