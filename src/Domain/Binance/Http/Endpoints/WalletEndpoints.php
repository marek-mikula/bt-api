<?php

namespace Domain\Binance\Http\Endpoints;

use App\Models\User;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Services\BinanceAuthenticator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WalletEndpoints
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
        private readonly Repository $config,
    ) {
    }

    /**
     * @throws BinanceRequestException
     */
    public function systemStatus(): Response
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
    public function accountStatus(User|KeyPairData $via): Response
    {
        $params = $this->authenticator->sign($via, []);

        $response = $this->authRequest($via)
            ->get('/sapi/v1/account/status', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    /**
     * @throws BinanceRequestException
     */
    public function allCoins(User|KeyPairData $via): Response
    {
        $params = $this->authenticator->sign($via, []);

        $response = $this->authRequest($via)
            ->get('/sapi/v1/capital/config/getall', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    /**
     * @throws BinanceRequestException
     */
    public function accountSnapshot(User|KeyPairData $via): Response
    {
        $params = $this->authenticator->sign($via, [
            'type' => 'SPOT',
            'startTime' => now()->subDay()->startOfDay()->getTimestampMs(),
        ]);

        $response = $this->authRequest($via)
            ->get('/sapi/v1/accountSnapshot', $params);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) $this->config->get('binance.url'));
    }

    private function authRequest(User|KeyPairData $via): PendingRequest
    {
        return $this->authenticator->authenticate($via, $this->request());
    }
}
