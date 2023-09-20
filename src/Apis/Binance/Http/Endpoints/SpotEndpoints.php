<?php

namespace Apis\Binance\Http\Endpoints;

use Apis\Binance\Data\KeyPairData;
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
        return $this->limiter->limit(20, BinanceEndpointEnum::S_ACCOUNT, [$this->spotClient, 'account'], $keyPair);
    }
}
