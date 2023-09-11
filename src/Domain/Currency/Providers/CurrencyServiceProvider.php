<?php

namespace Domain\Currency\Providers;

use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CurrencyDeferredServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
