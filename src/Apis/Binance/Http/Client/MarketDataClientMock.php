<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use App\Traits\MocksData;

class MarketDataClientMock implements MarketDataClientInterface
{
    use MocksData;

    public function exchangeInfo(): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'market-data/exchange-info.json'));

        return new BinanceResponse($response);
    }
}
