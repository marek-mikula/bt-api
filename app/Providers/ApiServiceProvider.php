<?php

namespace App\Providers;

use Apis\Binance\Providers\BinanceApiServiceProvider;
use Apis\Coinmarketcap\Providers\CoinmarketcapApiServiceProvider;
use Apis\Cryptopanic\Providers\CryptopanicApiServiceProvider;
use Apis\WhaleAlert\Providers\WhaleAlertApiServiceProvider;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(WhaleAlertApiServiceProvider::class);
        $this->app->register(CoinmarketcapApiServiceProvider::class);
        $this->app->register(BinanceApiServiceProvider::class);
        $this->app->register(CryptopanicApiServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
