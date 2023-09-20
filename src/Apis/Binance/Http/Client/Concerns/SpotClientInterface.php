<?php

namespace Apis\Binance\Http\Client\Concerns;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;

interface SpotClientInterface
{
    /**
     * @throws BinanceRequestException
     */
    public function account(KeyPairData $keyPair): BinanceResponse;
}
