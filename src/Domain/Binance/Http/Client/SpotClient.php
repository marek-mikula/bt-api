<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\SpotClientInterface;
use Domain\Binance\Services\BinanceAuthenticator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SpotClient implements SpotClientInterface
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
        private readonly Repository $config,
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
        return Http::baseUrl((string) $this->config->get('binance.url'));
    }

    private function authRequest(KeyPairData $keyPair): PendingRequest
    {
        return $this->authenticator->authenticate($keyPair, $this->request());
    }
}
