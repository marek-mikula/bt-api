<?php

namespace Domain\WhaleAlert\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class WhaleAlertRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/whale-alerts')
                ->as('api.whale_alerts.')
                ->group(__DIR__.'/../Routes/whale-alert.php');
        });
    }
}
