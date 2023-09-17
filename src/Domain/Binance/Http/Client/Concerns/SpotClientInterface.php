<?php

namespace Domain\Binance\Http\Client\Concerns;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;

interface SpotClientInterface
{
    /**
     * @throws BinanceRequestException
     */
    public function account(KeyPairData $keyPair): BinanceResponse;
}
