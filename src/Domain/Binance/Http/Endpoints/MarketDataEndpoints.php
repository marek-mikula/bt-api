<?php

namespace Domain\Binance\Http\Endpoints;

use Domain\Binance\Enums\BinanceEndpointEnum;
use Domain\Binance\Exceptions\BinanceBanException;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Domain\Binance\Services\BinanceLimiter;

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
