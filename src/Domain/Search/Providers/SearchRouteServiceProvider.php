<?php

namespace Domain\Search\Providers;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class SearchRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/search')
                ->as('api.search.')
                ->group(__DIR__.'/../Routes/search.php');
        });
    }
}
