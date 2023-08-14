<?php

namespace Domain\CoinRanking\Providers;

use Domain\CoinRanking\Http\CoinrankingClient;
use Domain\CoinRanking\Http\CoinrankingClientMock;
use Domain\CoinRanking\Http\Concerns\CoinrankingClientInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinrankingDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [];

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
