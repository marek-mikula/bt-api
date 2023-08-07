<?php

namespace App\Providers;

use App\Binance\Authenticator;
use App\Binance\BinanceApi;
use App\Binance\Endpoints\WalletEndpoints;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BinanceApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @var list<class-string>
     */
    private array $services = [
        BinanceApi::class,
        Authenticator::class,
        WalletEndpoints::class,
    ];

    public function register(): void
    {
        foreach ($this->services as $service) {
            $this->app->singleton($service);
        }
    }
}
