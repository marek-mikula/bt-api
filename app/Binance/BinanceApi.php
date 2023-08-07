<?php

namespace App\Binance;

use App\Binance\Endpoints\WalletEndpoints;

class BinanceApi
{
    public function __construct(
        public readonly WalletEndpoints $wallet,
    ) {
    }
}
