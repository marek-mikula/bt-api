<?php

namespace Apis\Binance\Http\Client\Concerns;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Data\OrderData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;

interface SpotClientInterface
{
    /**
     * @throws BinanceRequestException
     */
    public function account(KeyPairData $keyPair): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function placeOrder(KeyPairData $keyPair, OrderData $order): BinanceResponse;

    /**
     * @throws BinanceRequestException
     */
    public function order(KeyPairData $keyPair, OrderData $order): BinanceResponse;
}
