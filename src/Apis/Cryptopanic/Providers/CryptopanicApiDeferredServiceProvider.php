<?php

namespace Apis\Cryptopanic\Providers;

use Apis\Cryptopanic\Http\Client\Concerns\CryptopanicClientInterface;
use Apis\Cryptopanic\Http\Client\CryptopanicClient;
use Apis\Cryptopanic\Http\Client\CryptopanicClientMock;
use Apis\Cryptopanic\Http\CryptopanicApi;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CryptopanicApiDeferredServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        CryptopanicApi::class,
    ];

    public function register(): void
    {
        $this->app->singleton(CryptopanicClientInterface::class, static function () {
            return config('cryptopanic.mock')
                ? app(CryptopanicClientMock::class)
                : app(CryptopanicClient::class);
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
                CryptopanicClientInterface::class,
            ],
            $this->services,
        );
    }
}
