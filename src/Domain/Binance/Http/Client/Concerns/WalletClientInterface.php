<?php

namespace Domain\Binance\Http\Client\Concerns;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;

interface WalletClientInterface
{
    /**
     * @throws BinanceRequestException
     */
    public function systemStatus(): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function accountStatus(KeyPairData $keyPair): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function assets(KeyPairData $keyPair): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function allCoins(KeyPairData $keyPair): BinanceResponse;
}
