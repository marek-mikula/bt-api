<?php

namespace App\Binance;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;

class Authenticator
{
    public function authenticate(User $user, PendingRequest $request): PendingRequest
    {
        $request->withHeaders([
            'X-MBX-APIKEY' => $user->public_key,
        ]);

        return $request;
    }

    public function sign(User $user, array $params): array
    {
        $params = array_merge($params, [
            'timestamp' => Carbon::now()->getTimestampMs(),
            'recvWindow' => 5000,
        ]);

        $params['signature'] = hash_hmac('sha256', Arr::query($params), $user->secret_key);

        return $params;
    }
}
