<?php

namespace Domain\OpenExchangeRates\Providers;

use Domain\OpenExchangeRates\Cache\OpenExchangeRatesCache;
use Domain\OpenExchangeRates\Http\Client\Concerns\OpenExchangeRatesClientInterface;
use Domain\OpenExchangeRates\Http\Client\OpenExchangeRatesClient;
use Domain\OpenExchangeRates\Http\Client\OpenExchangeRatesClientMock;
use Domain\OpenExchangeRates\Http\OpenExchangeRatesApi;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class OpenExchangeRatesDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        OpenExchangeRatesApi::class,
        OpenExchangeRatesCache::class,
    ];

    public function register(): void
    {
        $this->app->singleton(OpenExchangeRatesClientInterface::class, static function () {
            return config('open-exchange-rates.mock')
                ? app(OpenExchangeRatesClientMock::class)
                : app(OpenExchangeRatesClient::class);
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
                OpenExchangeRatesClientInterface::class,
            ],
            $this->services,
        );
    }
}
