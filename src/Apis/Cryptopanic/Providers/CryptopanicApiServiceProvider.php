<?php

namespace Apis\Cryptopanic\Providers;

use Illuminate\Support\ServiceProvider;

class CryptopanicApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(CryptopanicApiDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Configs/cryptopanic.php', 'cryptopanic');
    }

    public function boot(): void
    {
        //
    }
}
