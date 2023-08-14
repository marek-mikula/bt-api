<?php

namespace Domain\CoinRanking\Providers;

use Illuminate\Support\ServiceProvider;

class CoinrankingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CoinrankingDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__ . '/../Configs/coinranking.php', 'coinranking');
    }

    public function boot(): void
    {
        //
    }
}
