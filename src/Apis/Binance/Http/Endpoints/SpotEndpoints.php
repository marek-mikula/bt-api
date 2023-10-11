<?php

namespace Apis\Binance\Http\Endpoints;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Data\OrderData;
use Apis\Binance\Enums\BinanceEndpointEnum;
use Apis\Binance\Exceptions\BinanceBanException;
use Apis\Binance\Exceptions\BinanceLimitException;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;
use Apis\Binance\Services\BinanceLimiter;

class SpotEndpoints
{
    public function __construct(
        private readonly BinanceLimiter $limiter,
        private readonly SpotClientInterface $spotClient,
    ) {
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function account(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(20, BinanceEndpointEnum::SPOT_ACCOUNT, [$this->spotClient, 'account'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function placeOrder(KeyPairData $keyPair, OrderData $order): BinanceResponse
    {
        return $this->limiter->limit(1, BinanceEndpointEnum::SPOT_PLACE_ORDER, [$this->spotClient, 'placeOrder'], $keyPair, $order);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function order(KeyPairData $keyPair, OrderData $order): BinanceResponse
    {
        return $this->limiter->limit(4, BinanceEndpointEnum::SPOT_GET_ORDER, [$this->spotClient, 'order'], $keyPair, $order);
    }
}
