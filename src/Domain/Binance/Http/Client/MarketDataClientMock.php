<?php

namespace Domain\Binance\Http\Client;

use App\Traits\MocksData;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;

class MarketDataClientMock implements MarketDataClientInterface
{
    use MocksData;

    public function exchangeInfo(): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'market-data/exchange-info.json'));

        return new BinanceResponse($response);
    }
}
