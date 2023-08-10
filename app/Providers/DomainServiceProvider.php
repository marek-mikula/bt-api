<?php

namespace App\Providers;

use Domain\Binance\Providers\BinanceServiceProvider;
use Domain\Coinmarketcap\Providers\CoinmarketcapServiceProvider;
use Domain\Dashboard\Providers\DashboardServiceProvider;
use Domain\Auth\Providers\AuthServiceProvider;
use Domain\Quiz\Providers\QuizServiceProvider;
use Domain\User\Providers\UserServiceProvider;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(BinanceServiceProvider::class);
        $this->app->register(CoinmarketcapServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(QuizServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
