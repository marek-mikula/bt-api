<?php

namespace Domain\Binance\Http;

use Domain\Binance\Http\Endpoints\MarketDataEndpoints;
use Domain\Binance\Http\Endpoints\SpotEndpoints;
use Domain\Binance\Http\Endpoints\WalletEndpoints;

class BinanceApi
{
    public function __construct(
        public readonly WalletEndpoints $wallet,
        public readonly MarketDataEndpoints $marketData,
        public readonly SpotEndpoints $spot,
    ) {
    }
}
