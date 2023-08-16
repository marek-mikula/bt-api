<?php

namespace Domain\Binance\Services;

use App\Models\User;
use Domain\Binance\Data\KeyPairData;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Authenticator
{
    public function authenticate(User|KeyPairData $via, PendingRequest $request): PendingRequest
    {
        $publicKey = $via instanceof User ? $via->public_key : $via->publicKey;

        return $request->withHeaders([
            'X-MBX-APIKEY' => $publicKey,
        ]);
    }

    public function sign(User|KeyPairData $via, array $params, int $window = 5000): array
    {
        $secretKey = $via instanceof User ? $via->secret_key : $via->secretKey;

        $params = array_merge($params, [
            'timestamp' => now()->getTimestampMs(),
            'recvWindow' => $window,
        ]);

        $params['signature'] = hash_hmac('sha256', Arr::query($params), $secretKey);

        return $params;
    }
}
