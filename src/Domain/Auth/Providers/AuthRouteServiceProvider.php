<?php

namespace Domain\Auth\Providers;

use Domain\Auth\Http\Middleware\MfaTokenMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthRouteServiceProvider extends RouteServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/auth')
                ->as('api.auth.')
                ->group(__DIR__.'/../Routes/auth.php');

            Route::middleware('api')
                ->prefix('/api/mfa')
                ->as('api.mfa.')
                ->group(__DIR__.'/../Routes/mfa.php');

            Route::middleware('api')
                ->prefix('/api/password-reset')
                ->as('api.password_reset.')
                ->group(__DIR__.'/../Routes/password-reset.php');
        });

        $this->aliasMiddleware('mfa', MfaTokenMiddleware::class);
    }
}
