<?php

namespace Apis\Binance\Http\Endpoints;

use Apis\Binance\Enums\BinanceEndpointEnum;
use Apis\Binance\Exceptions\BinanceBanException;
use Apis\Binance\Exceptions\BinanceLimitException;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Apis\Binance\Services\BinanceLimiter;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class MarketDataEndpoints
{
    public function __construct(
        private readonly BinanceLimiter $limiter,
        private readonly MarketDataClientInterface $marketDataClient,
    ) {
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function exchangeInfo(): BinanceResponse
    {
        return $this->limiter->limit(2, BinanceEndpointEnum::MARKET_DATA_EXCHANGE_INFO, [$this->marketDataClient, 'exchangeInfo'], null);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function symbolPrice(string|array $symbols): BinanceResponse
    {
        $symbols = collect(Arr::wrap($symbols));

        if ($symbols->isEmpty()) {
            throw new InvalidArgumentException('Symbols collection cannot be empty.');
        }

        $weight = $symbols->count() === 1 ? 2 : 4;

        return $this->limiter->limit($weight, BinanceEndpointEnum::MARKET_DATA_SYMBOL_PRICE, [$this->marketDataClient, 'symbolPrice'], null, $symbols);
    }
}
