<?php

namespace Apis\Binance\Http\Client\Concerns;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;

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
