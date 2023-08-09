<?php

namespace Domain\Binance\Providers;

use Illuminate\Support\ServiceProvider;

class BinanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(BinanceDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Configs/binance.php', 'binance');
    }
}
