<?php

namespace App\Providers;

use Domain\Alert\Providers\AlertServiceProvider;
use Domain\Auth\Providers\AuthServiceProvider;
use Domain\Binance\Providers\BinanceServiceProvider;
use Domain\Coinmarketcap\Providers\CoinmarketcapServiceProvider;
use Domain\Coinranking\Providers\CoinrankingServiceProvider;
use Domain\Cryptocurrency\Providers\CryptocurrencyServiceProvider;
use Domain\Dashboard\Providers\DashboardServiceProvider;
use Domain\Quiz\Providers\QuizServiceProvider;
use Domain\Search\Providers\SearchServiceProvider;
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
        $this->app->register(CoinrankingServiceProvider::class);
        $this->app->register(SearchServiceProvider::class);
        $this->app->register(CryptocurrencyServiceProvider::class);
        $this->app->register(AlertServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
