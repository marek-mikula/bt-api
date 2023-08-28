<?php

namespace Domain\Coinranking\Providers;

use Domain\Coinranking\Http\Client\CoinrankingClient;
use Domain\Coinranking\Http\Client\CoinrankingClientMock;
use Domain\Coinranking\Http\Client\Concerns\CoinrankingClientInterface;
use Domain\Coinranking\Http\CoinrankingApi;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinrankingDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        CoinrankingApi::class,
    ];

    public function register(): void
    {
        $this->app->singleton(CoinrankingClientInterface::class, static function () {
            return config('coinranking.mock')
                ? app(CoinrankingClientMock::class)
                : app(CoinrankingClient::class);
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
                CoinrankingClientInterface::class,
            ],
            $this->services,
        );
    }
}
