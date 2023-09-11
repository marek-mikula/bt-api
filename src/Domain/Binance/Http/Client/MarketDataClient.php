<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\MarketDataClientInterface;
use Domain\Binance\Services\BinanceAuthenticator;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class MarketDataClient implements MarketDataClientInterface
{
    public function __construct(
        private readonly BinanceAuthenticator $authenticator,
        private readonly Repository $config,
    ) {
    }

    public function tickerPrice(KeyPairData $keyPair, array|string $ticker): BinanceResponse
    {
        $params = [];

        if (is_string($ticker)) {
            $params['symbol'] = $ticker;
        } elseif (! empty($ticker)) {
            // wrap tickers with double quotes and
            // implode it to one string
            $params['symbols'] = collect($ticker)
                ->map(static fn (string $ticker): string => '"'.$ticker.'"')
                ->implode(',');

            // append and prepend square brackets to
            // the string
            $params['symbols'] = "[{$params['symbols']}]";
        }

        $response = $this->authRequest($keyPair)
            ->get('/api/v3/ticker/price', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function avgPrice(KeyPairData $keyPair, string $ticker): BinanceResponse
    {
        $response = $this->authRequest($keyPair)
            ->get('/api/v3/ticker/price', [
                'symbol' => $ticker,
            ]);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }

    public function exchangeInfo(KeyPairData $keyPair): BinanceResponse
    {
        $response = $this->request()->get('/api/v3/exchangeInfo', [
            'permissions' => 'SPOT', // get only spot assets
        ]);

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
