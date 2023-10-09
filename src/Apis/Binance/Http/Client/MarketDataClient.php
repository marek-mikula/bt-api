<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;

class MarketDataClient extends BinanceClient implements MarketDataClientInterface
{
    public function exchangeInfo(): BinanceResponse
    {
        $response = $this->request()->get('/api/v3/exchangeInfo', [
            'permissions' => 'SPOT', // get only spot assets
        ]);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }
}
