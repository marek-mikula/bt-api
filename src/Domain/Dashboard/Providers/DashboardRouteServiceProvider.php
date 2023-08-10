<?php

namespace Domain\Dashboard\Providers;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class DashboardRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/dashboard')
                ->as('api.dashboard.')
                ->group(__DIR__.'/../Routes/dashboard.php');
        });
    }
}
