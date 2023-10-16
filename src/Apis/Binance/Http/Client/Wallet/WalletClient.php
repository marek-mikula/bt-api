<?php

namespace Apis\Binance\Http\Client\Wallet;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\BinanceClient;
use Apis\Binance\Http\Client\Concerns\WalletClientInterface;

class WalletClient extends BinanceClient implements WalletClientInterface
{
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
        $params = $this->signParams($keyPair, []);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/account/status', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->signParams($keyPair, [
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
        $params = $this->signParams($keyPair, [
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
        $params = $this->signParams($keyPair, []);

        $response = $this->authRequest($keyPair)
            ->get('/sapi/v1/capital/config/getall', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }
}
