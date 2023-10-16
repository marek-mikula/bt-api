<?php

namespace Domain\Currency\Providers;

use Domain\Currency\Services\CryptocurrencyService;
use Domain\Currency\Services\CurrencyIndexer;
use Domain\Currency\Services\PairService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CurrencyDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        CurrencyIndexer::class,
        CryptocurrencyService::class,
        PairService::class,
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
