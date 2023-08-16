<?php

namespace Domain\Search\Providers;

use Illuminate\Support\ServiceProvider;

class SearchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SearchRouteServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
