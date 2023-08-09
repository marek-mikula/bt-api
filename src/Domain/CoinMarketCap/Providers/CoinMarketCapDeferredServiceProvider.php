<?php

namespace Domain\CoinMarketCap\Providers;

use Domain\CoinMarketCap\Http\CoinMarketCapClient;
use Domain\CoinMarketCap\Http\CoinMarketCapClientMock;
use Domain\CoinMarketCap\Http\Concerns\CoinMarketCapClientInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinMarketCapDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(CoinMarketCapClientInterface::class, static function () {
            return config('services.coinmarketcap.mock')
                ? app(CoinMarketCapClientMock::class)
                : app(CoinMarketCapClient::class);
        });
    }

    /**
     * @return list<class-string>
     */
    public function provides(): array
    {
        return array_merge(
            [
                CoinMarketCapClientInterface::class,
            ],
        );
    }
}
