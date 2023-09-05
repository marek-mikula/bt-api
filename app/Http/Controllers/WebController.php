<?php

namespace App\Http\Controllers;

use App\Models\User;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\BinanceApi;
use Illuminate\Contracts\View\View;

class WebController extends Controller
{
    public function welcome(): View
    {
        return view('welcome');
    }

    public function test(): void
    {
        /** @var BinanceApi $api */
        $api = app(BinanceApi::class);

        /** @var User $user */
        $user = request()->user('api');

        dd($api->wallet->accountSnapshot(KeyPairData::fromUser($user)));
    }
}
