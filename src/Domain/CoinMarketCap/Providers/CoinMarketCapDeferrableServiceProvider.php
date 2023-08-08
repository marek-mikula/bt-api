<?php

namespace Domain\CoinMarketCap\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class CoinMarketCapDeferrableServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        //
    }

    /**
     * @return list<class-string>
     */
    public function provides(): array
    {
        return array_merge(
            [],
        );
    }
}
