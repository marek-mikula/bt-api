<?php

namespace Domain\Binance\Http\Endpoints;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Domain\Binance\Exceptions\BinanceBanException;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\SpotClientInterface;
use Domain\Binance\Services\BinanceLimiter;

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
