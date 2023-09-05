<?php

namespace Domain\Binance\Http\Client\Concerns;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Illuminate\Http\Client\Response;

interface WalletClientInterface
{
    /**
     * @throws BinanceRequestException
     */
    public function systemStatus(): Response;

    /**
     * @throws BinanceRequestException
     */
    public function accountStatus(KeyPairData $keyPair): Response;

    /**
     * @throws BinanceRequestException
     */
    public function accountSnapshot(KeyPairData $keyPair): Response;

    /**
     * @throws BinanceRequestException
     */
    public function assets(KeyPairData $keyPair): Response;
}
