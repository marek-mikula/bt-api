<?php

namespace Domain\Binance\Providers;

use Domain\Binance\Http\BinanceClient;
use Domain\Binance\Http\Endpoints\WalletEndpoints;
use Domain\Binance\Services\Authenticator;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BinanceDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        BinanceClient::class,

        // endpoints
        WalletEndpoints::class,

        // services
        Authenticator::class,
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
