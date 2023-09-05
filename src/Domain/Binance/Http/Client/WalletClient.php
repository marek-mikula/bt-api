<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Domain\Binance\Services\BinanceAuthenticator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WalletClient implements WalletClientInterface
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
        private readonly Repository $config,
    ) {
    }

    public function systemStatus(): Response
    {
        $response = $this->request()
            ->get('/sapi/v1/system/status');

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    public function accountStatus(KeyPairData $keyPair): Response
    {
        $params = $this->authenticator->sign($keyPair, []);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/account/status', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    public function accountSnapshot(KeyPairData $keyPair): Response
    {
        $params = $this->authenticator->sign($keyPair, [
            'type' => 'SPOT',
            'startTime' => now()->subDay()->startOfDay()->getTimestampMs(),
        ]);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/accountSnapshot', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    public function assets(KeyPairData $keyPair): Response
    {
        $params = $this->authenticator->sign($keyPair, [
            'needBtcValuation' => '1',
        ]);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/asset/getUserAsset', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
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
