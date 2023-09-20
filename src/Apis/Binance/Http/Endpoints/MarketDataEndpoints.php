<?php

namespace Apis\Binance\Http\Endpoints;

use Apis\Binance\Enums\BinanceEndpointEnum;
use Apis\Binance\Exceptions\BinanceBanException;
use Apis\Binance\Exceptions\BinanceLimitException;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Apis\Binance\Services\BinanceLimiter;

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
        return $this->limiter->limit(2, BinanceEndpointEnum::MD_EXCHANGE_INFO, [$this->marketDataClient, 'exchangeInfo'], null);
    }
}
