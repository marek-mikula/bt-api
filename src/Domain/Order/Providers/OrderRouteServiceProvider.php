<?php

namespace Domain\Order\Providers;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class OrderRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/orders')
                ->as('api.orders.')
                ->group(__DIR__.'/../Routes/order.php');
        });
    }
}
