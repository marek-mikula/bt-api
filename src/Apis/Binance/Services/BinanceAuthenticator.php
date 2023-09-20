<?php

namespace Apis\Binance\Services;

use Apis\Binance\Data\KeyPairData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class BinanceAuthenticator
{
    public function authenticate(KeyPairData $keyPair, PendingRequest $request): PendingRequest
    {
        return $request->withHeaders([
            'X-MBX-APIKEY' => $keyPair->publicKey,
        ]);
    }

    public function sign(KeyPairData $keyPair, array $params, int $window = 5000): array
    {
        $params = array_merge($params, [
            'timestamp' => now()->getTimestampMs(),
            'recvWindow' => $window,
        ]);

        $params['signature'] = hash_hmac('sha256', Arr::query($params), $keyPair->secretKey);

        return $params;
    }
}
