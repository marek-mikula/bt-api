<?php

namespace Domain\Binance\Http\Client;

use App\Traits\MocksData;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;

class MarketDataClientMock implements MarketDataClientInterface
{
    use MocksData;

    public function tickerPrice(KeyPairData $keyPair, array|string $ticker): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'market-data/ticker-price.json'));

        return new BinanceResponse($response);
    }

    public function avgPrice(KeyPairData $keyPair, string $ticker): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'market-data/avg-price.json'));

        return new BinanceResponse($response);
    }

    public function exchangeInfo(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'market-data/exchange-info.json'));

        return new BinanceResponse($response);
    }
}
