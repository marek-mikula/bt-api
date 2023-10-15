<?php

namespace Domain\Cryptocurrency\Providers;

use App\Models\Currency;
use App\Models\CurrencyPair;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class CryptocurrencyRouteServiceProvider extends RouteServiceProvider
{
    public function boot(): void
    {
        $this->bootBindings();

        $this->routes(static function (): void {
            Route::middleware('api')
                ->prefix('/api/cryptocurrencies')
                ->as('api.cryptocurrency.')
                ->group(__DIR__.'/../Routes/cryptocurrency.php');
        });
    }

    private function bootBindings(): void
    {
        Route::bind('cryptocurrency', static function (string $value): Currency {
            /** @var Currency $cryptocurrency */
            $cryptocurrency = Currency::query()
                ->crypto()
                ->findOrFail((int) $value);

            return $cryptocurrency;
        });

        Route::bind('pair', static function (string $value): CurrencyPair {
            /** @var CurrencyPair $pair */
            $pair = CurrencyPair::query()
                ->ofSymbol($value)
                ->firstOrFail();

            return $pair;
        });
    }
}
