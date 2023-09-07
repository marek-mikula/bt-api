<?php

namespace Domain\Binance\Http\Client\Concerns;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;

interface MarketDataClientInterface
{
    /**
     * @param  list<string>|string  $ticker i.e. BTCLTC, ETHLTC..., if empty
     * all pairs are returned
     *
     * @throws BinanceRequestException
     */
    public function tickerPrice(KeyPairData $keyPair, array|string $ticker): BinanceResponse;

    /**
     * @param  string  $ticker i.e. BTCLTC, ETHLTC...
     *
     * @throws BinanceRequestException
     */
    public function avgPrice(KeyPairData $keyPair, string $ticker): BinanceResponse;
}
