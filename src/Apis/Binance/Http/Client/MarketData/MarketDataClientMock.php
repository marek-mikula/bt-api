<?php

namespace Apis\Binance\Http\Client\MarketData;

use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use App\Traits\MocksData;
use Illuminate\Support\Collection;

class MarketDataClientMock implements MarketDataClientInterface
{
    use MocksData;

    public function exchangeInfo(): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'market-data/exchange-info.json'));

        return new BinanceResponse($response);
    }

    public function symbolPrice(Collection $symbols): BinanceResponse
    {
        $data = collect($this->mockData('Binance', 'market-data/symbol-price.json'))
            ->filter(static function (array $item) use ($symbols): bool {
                return $symbols->contains($item['symbol']);
            });

        // take only first item if number of
        // symbols is 1
        if ($symbols->count() === 1) {
            $data = $data->first();
        }

        $response = response_from_client(data: $data);

        return new BinanceResponse($response);
    }
}
