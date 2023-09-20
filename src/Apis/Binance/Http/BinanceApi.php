<?php

namespace Apis\Binance\Http;

use Apis\Binance\Http\Endpoints\MarketDataEndpoints;
use Apis\Binance\Http\Endpoints\SpotEndpoints;
use Apis\Binance\Http\Endpoints\WalletEndpoints;

class BinanceApi
{
    public function __construct(
        public readonly MarketDataEndpoints $marketData,
        public readonly WalletEndpoints $wallet,
        public readonly SpotEndpoints $spot,
    ) {
    }
}
