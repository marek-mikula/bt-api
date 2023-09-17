<?php

namespace Domain\Coinmarketcap\Providers;

use Domain\Coinmarketcap\Http\Client\CoinmarketcapClient;
use Domain\Coinmarketcap\Http\Client\CoinmarketcapClientMock;
use Domain\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Domain\Coinmarketcap\Http\CoinmarketcapApi;
use Domain\Coinmarketcap\Services\CoinmarketcapLimiter;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinmarketcapDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        CoinmarketcapApi::class,
        CoinmarketcapLimiter::class,
    ];

    public function register(): void
    {
        $this->app->singleton(CoinmarketcapClientInterface::class, static function () {
            return config('coinmarketcap.mock')
                ? app(CoinmarketcapClientMock::class)
                : app(CoinmarketcapClient::class);
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
                CoinmarketcapClientInterface::class,
            ],
            $this->services,
        );
    }
}
