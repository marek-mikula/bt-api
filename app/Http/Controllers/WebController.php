<?php

namespace App\Http\Controllers;

use Domain\Coinmarketcap\Services\CoinmarketcapService;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        /** @var CoinmarketcapService $service */
        $service = app(CoinmarketcapService::class);

        dd($service->getCryptocurrenciesByCap()->toArray());
    }
}
