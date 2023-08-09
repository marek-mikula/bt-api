<?php

namespace Domain\Binance\Services;

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

    public function sign(User $user, array $params, int $window = 5000): array
    {
        $params = array_merge($params, [
            'timestamp' => Carbon::now()->getTimestampMs(),
            'recvWindow' => $window,
        ]);

        $params['signature'] = hash_hmac('sha256', Arr::query($params), $user->secret_key);

        return $params;
    }
}
