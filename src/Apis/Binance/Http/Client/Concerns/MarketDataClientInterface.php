<?php

namespace Apis\Binance\Http\Client\Concerns;

use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Illuminate\Support\Collection;

interface MarketDataClientInterface
{
    /**
     * @throws BinanceRequestException
     */
    public function exchangeInfo(): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function symbolPrice(Collection $symbols): BinanceResponse;
}
