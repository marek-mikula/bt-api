<?php

namespace Apis\Binance\Http\Client\Concerns;

use Apis\Binance\Http\BinanceResponse;

interface MarketDataClientInterface
{
    public function exchangeInfo(): BinanceResponse;
}
