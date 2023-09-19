<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Apis\Binance\Services\BinanceAuthenticator;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MarketDataClient implements MarketDataClientInterface
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
    ) {
    }

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

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) config('binance.url'));
    }

    private function authRequest(KeyPairData $keyPair): PendingRequest
    {
        return $this->authenticator->authenticate($keyPair, $this->request());
    }
}
