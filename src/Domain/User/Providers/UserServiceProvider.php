<?php

namespace Domain\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(UserDeferrableServiceProvider::class);
        $this->app->register(UserRouteServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
