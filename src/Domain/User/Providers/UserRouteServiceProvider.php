<?php

namespace Domain\User\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class UserRouteServiceProvider extends RouteServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/user')
                ->as('api.user.')
                ->group(__DIR__.'/../Routes/user.php');
        });
    }
}
