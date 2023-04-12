<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeServiceProvider as BaseTelescopeServiceProvider;

class TelescopeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(BaseTelescopeServiceProvider::class);

        Telescope::ignoreMigrations();

        Telescope::night();

        Telescope::hideRequestParameters([
            'password',
            'passwordConfirm',
            'publicKey',
            'secretKey',
        ]);

        Telescope::hideResponseParameters([
            'data.accessToken',
        ]);
    }

    public function boot(): void
    {
        //
    }
}
