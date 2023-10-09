<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class BinanceClient
{
    protected bool $supportsTestnet = false;

    /**
     * Creates new pending request to Binance API
     */
    protected function request(): PendingRequest
    {
        $url = (string) config('binance.url');

        // swap URL if testnet is enabled and
        // should be used
        if ($this->supportsTestnet && config('binance.testnet.enabled')) {
            $url = (string) config('binance.testnet.url');
        }

        return Http::baseUrl($url);
    }

    /**
     * Creates new pending authenticated request
     * to Binance API with public key appended as
     * header value
     */
    protected function authRequest(KeyPairData $keyPair): PendingRequest
    {
        $key = $keyPair->publicKey;

        // swap key if testnet is enabled and
        // should be used
        if ($this->supportsTestnet && config('binance.testnet.enabled')) {
            $key = (string) config('binance.testnet.keys.public');
        }

        return $this->request()->withHeaders([
            'X-MBX-APIKEY' => $key,
        ]);
    }

    /**
     * Signs the params array using HMAC and appends
     * the signature to the array
     */
    protected function signParams(KeyPairData $keyPair, array $params, int $window = 5000): array
    {
        $params = array_merge($params, [
            'timestamp' => now()->getTimestampMs(),
            'recvWindow' => $window,
        ]);

        $key = $keyPair->secretKey;

        // swap key if testnet is enabled and
        // should be used
        if ($this->supportsTestnet && config('binance.testnet.enabled')) {
            $key = (string) config('binance.testnet.keys.secret');
        }

        $params['signature'] = hash_hmac('sha256', Arr::query($params), $key);

        return $params;
    }
}
