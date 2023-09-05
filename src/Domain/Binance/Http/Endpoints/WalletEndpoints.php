<?php

namespace Domain\Binance\Http\Endpoints;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Domain\Binance\Services\BinanceLimiter;

class WalletEndpoints implements WalletClientInterface
{
    public function __construct(
        private readonly BinanceLimiter $limiter,
        private readonly WalletClientInterface $walletClient,
    ) {
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function systemStatus(): BinanceResponse
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_SYSTEM_STATUS, [$this->walletClient, 'systemStatus'], null);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function accountStatus(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_ACCOUNT_STATUS, [$this->walletClient, 'accountStatus'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_ACCOUNT_SNAPSHOT, [$this->walletClient, 'accountSnapshot'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function assets(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_ASSETS, [$this->walletClient, 'assets'], $keyPair);
    }
}
