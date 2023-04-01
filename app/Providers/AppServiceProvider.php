<?php

namespace App\Providers;

use App\Enums\EnvEnum;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // register telescope service provider for non-prod only
        if (! $this->app->environment(EnvEnum::PRODUCTION->value)) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        //
    }
}
