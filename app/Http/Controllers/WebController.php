<?php

namespace App\Http\Controllers;

use Domain\Coinranking\Http\CoinrankingApi;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        /** @var CoinrankingApi $api */
        $api = app(CoinrankingApi::class);

        dd($api->search('btc')->json());
    }
}
