<?php

namespace Domain\Binance\Http\Endpoints;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Enums\BinanceEndpointEnum;
use Domain\Binance\Exceptions\BinanceLimitException;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Domain\Binance\Services\BinanceLimiter;
use Illuminate\Http\Client\Response;

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
    public function systemStatus(): Response
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_SYSTEM_STATUS, [$this->walletClient, 'systemStatus'], null);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function accountStatus(KeyPairData $keyPair): Response
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_ACCOUNT_STATUS, [$this->walletClient, 'accountStatus'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function accountSnapshot(KeyPairData $keyPair): Response
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_ACCOUNT_SNAPSHOT, [$this->walletClient, 'accountSnapshot'], $keyPair);
    }

    /**
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     */
    public function assets(KeyPairData $keyPair): Response
    {
        return $this->limiter->limit(BinanceEndpointEnum::W_ASSETS, [$this->walletClient, 'assets'], $keyPair);
    }
}
