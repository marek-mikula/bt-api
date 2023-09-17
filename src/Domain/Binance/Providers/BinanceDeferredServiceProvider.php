<?php

namespace Domain\Binance\Providers;

use Domain\Binance\Http\BinanceApi;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Domain\Binance\Http\Client\WalletClient;
use Domain\Binance\Http\Client\WalletClientMock;
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
            ],
            $this->services,
        );
    }
}
