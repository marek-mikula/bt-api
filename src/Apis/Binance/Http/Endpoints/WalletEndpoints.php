<?php

namespace Apis\Binance\Http\Endpoints;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Enums\BinanceEndpointEnum;
use Apis\Binance\Exceptions\BinanceBanException;
use Apis\Binance\Exceptions\BinanceLimitException;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\WalletClientInterface;
use Apis\Binance\Services\BinanceLimiter;

class WalletEndpoints
{
    public function __construct(
        private readonly BinanceLimiter $limiter,
        private readonly WalletClientInterface $walletClient,
    ) {
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function systemStatus(): BinanceResponse
    {
        return $this->limiter->limit(1, BinanceEndpointEnum::W_SYSTEM_STATUS, [$this->walletClient, 'systemStatus'], null);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function accountStatus(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(1, BinanceEndpointEnum::W_ACCOUNT_STATUS, [$this->walletClient, 'accountStatus'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(2400, BinanceEndpointEnum::W_ACCOUNT_SNAPSHOT, [$this->walletClient, 'accountSnapshot'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function assets(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(5, BinanceEndpointEnum::W_ASSETS, [$this->walletClient, 'assets'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceBanException
     * @throws BinanceRequestException
     */
    public function allCoins(KeyPairData $keyPair): BinanceResponse
    {
        return $this->limiter->limit(10, BinanceEndpointEnum::W_ALL_COINS, [$this->walletClient, 'allCoins'], $keyPair);
    }
}
