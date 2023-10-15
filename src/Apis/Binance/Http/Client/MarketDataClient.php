<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Illuminate\Support\Collection;

class MarketDataClient extends BinanceClient implements MarketDataClientInterface
{
    public function exchangeInfo(): BinanceResponse
    {
        $response = $this->request()->get('/api/v3/exchangeInfo', [
            'permissions' => 'SPOT', // get only spot assets
        ]);

        $response = new BinanceResponse($response);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    public function symbolPrice(Collection $symbols): BinanceResponse
    {
        $params = [];

        if ($symbols->count() === 1) {
            $params['symbol'] = $symbols->first();
        } else {
            $params['symbols'] = '['.$symbols
                ->map(static fn (string $symbol): string => '"'.$symbol.'"')
                ->implode(',').']';
        }

        $response = $this->request()->get('/api/v3/ticker/price', $params);

        $response = new BinanceResponse($response);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }
}
