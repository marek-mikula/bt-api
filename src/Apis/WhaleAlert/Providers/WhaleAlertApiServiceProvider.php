<?php

namespace Apis\WhaleAlert\Providers;

use Illuminate\Support\ServiceProvider;

class WhaleAlertApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(WhaleAlertApiDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Configs/whale-alert.php', 'whale-alert');
    }

    public function boot(): void
    {
        //
    }
}
