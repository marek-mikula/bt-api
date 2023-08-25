<?php

namespace Domain\Cryptocurrency\Providers;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class CryptocurrencyRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/cryptocurrencies')
                ->as('api.cryptocurrency.')
                ->group(__DIR__.'/../Routes/cryptocurrency.php');
        });
    }
}
