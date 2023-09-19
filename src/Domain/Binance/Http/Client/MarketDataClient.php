<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Domain\Binance\Services\BinanceAuthenticator;
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
