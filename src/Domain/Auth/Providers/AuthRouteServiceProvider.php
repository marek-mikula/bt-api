<?php

namespace Domain\Auth\Providers;

use Domain\Auth\Http\Middleware\MfaTokenMiddleware;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/auth')
                ->as('api.auth.')
                ->group(domain_path('Auth', 'Routes/auth.php'));

            Route::middleware('api')
                ->prefix('/api/mfa')
                ->as('api.mfa.')
                ->group(domain_path('Auth', 'Routes/mfa.php'));

            Route::middleware('api')
                ->prefix('/api/password-reset')
                ->as('api.password_reset.')
                ->group(domain_path('Auth', 'Routes/password-reset.php'));
        });

        $this->aliasMiddleware('mfa', MfaTokenMiddleware::class);
    }
}
