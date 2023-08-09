<?php

namespace Domain\Coinmarketcap\Providers;

use Illuminate\Support\ServiceProvider;

class CoinmarketcapServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CoinmarketcapDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Configs/coinmarketcap.php', 'coinmarketcap');
    }
}
