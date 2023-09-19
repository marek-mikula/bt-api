<?php

namespace Apis\Binance\Providers;

use Illuminate\Support\ServiceProvider;

class BinanceApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(BinanceApiDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Configs/binance.php', 'binance');
    }

    public function boot(): void
    {
        //
    }
}
