<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Illuminate\Support\Str;

class MarketDataClientMock implements MarketDataClientInterface
{
    public function tickerPrice(KeyPairData $keyPair, array|string $ticker): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('ticker-price.json'));

        return new BinanceResponse($response);
    }

    public function avgPrice(KeyPairData $keyPair, string $ticker): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('avg-price.json'));

        return new BinanceResponse($response);
    }

    private function mockData(string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: domain_path('Binance', "Resources/mocks/market-data/{$path}")
        );

        return json_decode(json: $json, associative: true);
    }
}
