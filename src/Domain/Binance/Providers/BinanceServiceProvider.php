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
        //
    }
}
