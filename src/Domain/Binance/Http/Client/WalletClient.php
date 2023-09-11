<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Domain\Binance\Services\BinanceAuthenticator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class WalletClient implements WalletClientInterface
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
        private readonly Repository $config,
    ) {
    }

    public function systemStatus(): BinanceResponse
    {
        $response = $this->request()
            ->get('/sapi/v1/system/status');

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function accountStatus(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->authenticator->sign($keyPair, []);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/account/status', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->authenticator->sign($keyPair, [
            'type' => 'SPOT',
            'startTime' => now()->subDay()->startOfDay()->getTimestampMs(),
        ]);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/accountSnapshot', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function assets(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->authenticator->sign($keyPair, [
            'needBtcValuation', // does not work :(
        ]);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/asset/getUserAsset', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function allCoins(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->authenticator->sign($keyPair, []);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/capital/config/getall', $params);

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
