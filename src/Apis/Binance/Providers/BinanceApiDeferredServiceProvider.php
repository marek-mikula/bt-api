<?php

namespace Apis\Binance\Providers;

use Apis\Binance\Http\BinanceApi;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;
use Apis\Binance\Http\Client\Concerns\WalletClientInterface;
use Apis\Binance\Http\Client\MarketDataClient;
use Apis\Binance\Http\Client\MarketDataClientMock;
use Apis\Binance\Http\Client\SpotClient;
use Apis\Binance\Http\Client\SpotClientMock;
use Apis\Binance\Http\Client\WalletClient;
use Apis\Binance\Http\Client\WalletClientMock;
use Apis\Binance\Http\Endpoints\MarketDataEndpoints;
use Apis\Binance\Http\Endpoints\SpotEndpoints;
use Apis\Binance\Http\Endpoints\WalletEndpoints;
use Apis\Binance\Services\BinanceAuthenticator;
use Apis\Binance\Services\BinanceKeyValidator;
use Apis\Binance\Services\BinanceLimiter;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BinanceApiDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
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
