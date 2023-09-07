<?php

namespace Domain\OpenExchangeRates\Providers;

use Illuminate\Support\ServiceProvider;

class OpenExchangeRatesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(OpenExchangeRatesDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Configs/open-exchange-rates.php', 'open-exchange-rates');
    }

    public function boot(): void
    {
        //
    }
}
