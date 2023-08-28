<?php

namespace Domain\Binance\Providers;

use Domain\Binance\Http\BinanceClient;
use Domain\Binance\Http\Endpoints\WalletEndpoints;
use Domain\Binance\Services\BinanceAuthenticator;
use Domain\Binance\Services\BinanceKeyValidator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BinanceDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        // client
        BinanceClient::class,

        // endpoints
        WalletEndpoints::class,

        // services
        BinanceAuthenticator::class,
        BinanceKeyValidator::class,
    ];

    public function register(): void
    {
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
            [],
            $this->services,
        );
    }
}
