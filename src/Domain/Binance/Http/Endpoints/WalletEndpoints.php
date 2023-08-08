<?php

namespace Domain\Binance\Http\Endpoints;

use App\Models\User;
use Carbon\Carbon;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Services\Authenticator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WalletEndpoints
{
    public function __construct(
        private readonly Authenticator $authenticator,
        private readonly Repository $config,
    ) {
    }

    /**
     * @throws BinanceRequestException
     */
    public function status(): Response
    {
        $response = $this->request()
            ->get('/sapi/v1/system/status');

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    /**
     * @throws BinanceRequestException
     */
    public function allCoins(User $user): Response
    {
        $params = $this->authenticator->sign($user, []);

        $response = $this->authRequest($user)
            ->get('/sapi/v1/capital/config/getall', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    /**
     * @throws BinanceRequestException
     */
    public function accountSnapshot(User $user): Response
    {
        $params = $this->authenticator->sign($user, [
            'type' => 'SPOT',
            'startTime' => Carbon::now()->subDay()->startOfDay()->getTimestampMs(),
        ]);

        $response = $this->authRequest($user)
            ->get('/sapi/v1/accountSnapshot', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl($this->config->get('services.binance.url'));
    }

    private function authRequest(User $user): PendingRequest
    {
        return $this->authenticator->authenticate($user, $this->request());
    }
}
