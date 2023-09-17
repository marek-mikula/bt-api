<?php

namespace Domain\Binance\Providers;

use Domain\Binance\Http\BinanceApi;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Domain\Binance\Http\Client\Concerns\SpotClientInterface;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Domain\Binance\Http\Client\MarketDataClient;
use Domain\Binance\Http\Client\MarketDataClientMock;
use Domain\Binance\Http\Client\SpotClient;
use Domain\Binance\Http\Client\SpotClientMock;
use Domain\Binance\Http\Client\WalletClient;
use Domain\Binance\Http\Client\WalletClientMock;
use Domain\Binance\Http\Endpoints\MarketDataEndpoints;
use Domain\Binance\Http\Endpoints\SpotEndpoints;
use Domain\Binance\Http\Endpoints\WalletEndpoints;
use Domain\Binance\Services\BinanceAuthenticator;
use Domain\Binance\Services\BinanceKeyValidator;
use Domain\Binance\Services\BinanceLimiter;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BinanceDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        // api
        BinanceApi::class,

        // endpoints
        WalletEndpoints::class,
        MarketDataEndpoints::class,
        SpotEndpoints::class,

        // services
        BinanceAuthenticator::class,
        BinanceKeyValidator::class,
        BinanceLimiter::class,
    ];

    public function register(): void
    {
        $this->app->singleton(WalletClientInterface::class, static function () {
            return config('binance.mock')
                ? app(WalletClientMock::class)
                : app(WalletClient::class);
        });

        $this->app->singleton(MarketDataClientInterface::class, static function () {
            return config('binance.mock')
                ? app(MarketDataClientMock::class)
                : app(MarketDataClient::class);
        });

        $this->app->singleton(SpotClientInterface::class, static function () {
            return config('binance.mock')
                ? app(SpotClientMock::class)
                : app(SpotClient::class);
        });

        foreach ($this->services as $service) {
            $this->app->singleton($service);
        }
    }

    /**
     * @return list<class-string>
     */
    public function provides(): array
    {
        return array_merge(
            [
                WalletClientInterface::class,
                MarketDataClientInterface::class,
            ],
            $this->services,
        );
    }
}
