<?php

namespace App\Providers;

use Domain\Binance\Providers\BinanceServiceProvider;
use Domain\CoinMarketCap\Providers\CoinMarketCapServiceProvider;
use Domain\Quiz\Providers\QuizServiceProvider;
use Domain\User\Providers\UserServiceProvider;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(BinanceServiceProvider::class);
        $this->app->register(CoinMarketCapServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        $this->app->register(QuizServiceProvider::class);
        $this->app->register(UserServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
