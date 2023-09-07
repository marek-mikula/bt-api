<?php

namespace Domain\Binance\Http\Endpoints;

use Domain\Binance\Data\KeyPairData;
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
    public function tickerPrice(KeyPairData $keyPair, array|string|null $ticker): BinanceResponse
    {
        $ticker = collect(empty($ticker) ? [] : (is_string($ticker) ? [$ticker] : $ticker));

        if ($ticker->count() === 0 || $ticker->count() > 1) {
            $weight = 4;
            $ticker = $ticker->all();
        } else {
            $weight = 2;
            $ticker = (string) $ticker->first();
        }

        return $this->limiter->limit($weight, BinanceEndpointEnum::MD_TICKER_PRICE, [$this->marketDataClient, 'tickerPrice'], $keyPair, $ticker);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function avgPrice(KeyPairData $keyPair, string $ticker): BinanceResponse
    {
        return $this->limiter->limit(2, BinanceEndpointEnum::MD_AVG_PRICE, [$this->marketDataClient, 'avgPrice'], $keyPair, $ticker);
    }
}
