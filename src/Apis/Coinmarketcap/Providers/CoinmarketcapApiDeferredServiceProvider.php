<?php

namespace Apis\Coinmarketcap\Providers;

use Apis\Coinmarketcap\Http\Client\CoinmarketcapClient;
use Apis\Coinmarketcap\Http\Client\CoinmarketcapClientMock;
use Apis\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use Apis\Coinmarketcap\Services\CoinmarketcapLimiter;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinmarketcapApiDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
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
