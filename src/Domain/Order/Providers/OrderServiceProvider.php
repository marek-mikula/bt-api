<?php

namespace Domain\Order\Providers;

use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->registerDeferredProvider(OrderDeferredServiceProvider::class);
        $this->app->register(OrderRouteServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
