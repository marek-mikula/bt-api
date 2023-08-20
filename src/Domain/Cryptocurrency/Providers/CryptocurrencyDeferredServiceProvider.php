<?php

namespace Domain\Cryptocurrency\Providers;

use Domain\Cryptocurrency\Services\CryptocurrencyService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CryptocurrencyDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        CryptocurrencyService::class,
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
