<?php

namespace Apis\Binance\Http\Client\Concerns;

use Apis\Binance\Http\BinanceResponse;
use Illuminate\Support\Collection;

interface MarketDataClientInterface
{
    public function exchangeInfo(): BinanceResponse;

    public function symbolPrice(Collection $symbols): BinanceResponse;
}
