<?php

namespace Domain\Coinmarketcap\Providers;

use Domain\Coinmarketcap\Http\CoinmarketcapClient;
use Domain\Coinmarketcap\Http\CoinmarketcapClientMock;
use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinmarketcapDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [];

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
