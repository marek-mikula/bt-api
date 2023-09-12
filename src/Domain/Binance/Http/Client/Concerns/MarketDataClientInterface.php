<?php

namespace Domain\Binance\Http\Client\Concerns;

use Domain\Binance\Http\BinanceResponse;

interface MarketDataClientInterface
{
    public function exchangeInfo(): BinanceResponse;
}
