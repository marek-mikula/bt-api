<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;
use Apis\Binance\Services\BinanceAuthenticator;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SpotClient implements SpotClientInterface
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
    ) {
    }

    public function account(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->authenticator->sign($keyPair, []);

        $response = $this->authRequest($keyPair)
            ->get('/api/v3/account', $params);

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
