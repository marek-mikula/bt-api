<?php

namespace App\Http\Controllers;

use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        /** @var CoinmarketcapClientInterface $client */
        $client = app(CoinmarketcapClientInterface::class);

        dd($client->coinMetadata([
            1,
            1027,
            825,

            13636,
            10326,
            10430,

            26721,
            23396,

            16914,
            19191,

            26546,
            20894,
            21394,

            8733,
            9217,
            27802,

            27744,
            1807,
            16298,

            19965,
            23622,
            17772,

            1396,
            1468,
            1474,

            21587,
            21593,
            21616,
        ])->json());
    }
}
