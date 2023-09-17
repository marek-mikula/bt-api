<?php

namespace Domain\WhaleAlert\Providers;

use Illuminate\Support\ServiceProvider;

class WhaleAlertServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(WhaleAlertDeferredServiceProvider::class);

        $this->mergeConfigFrom(__DIR__.'/../Configs/whale-alert.php', 'whale-alert');
    }

    public function boot(): void
    {
        //
    }
}
