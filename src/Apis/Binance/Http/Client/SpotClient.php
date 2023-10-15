<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Data\OrderData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;

class SpotClient extends BinanceClient implements SpotClientInterface
{
    protected bool $supportsTestnet = true;

    public function account(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->signParams($keyPair, []);

        $response = $this->authRequest($keyPair)->get('/api/v3/account', $params);

        $response = new BinanceResponse($response);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    public function placeOrder(KeyPairData $keyPair, OrderData $order): BinanceResponse
    {
        $precision = $order->pair->base_currency_precision;

        $params = $this->signParams($keyPair, [
            'symbol' => $order->pair->symbol,
            'side' => $order->side->name,
            'type' => 'MARKET',
            'quantity' => sprintf("%.{$precision}f", $order->quantity), // transform float to formatted number
            'newClientOrderId' => $order->uuid,
        ]);

        $response = $this->authRequest($keyPair)
            ->asForm()
            ->post('/api/v3/order', $params);

        $response = new BinanceResponse($response);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }

    public function order(KeyPairData $keyPair, string $symbol, string $uuid): BinanceResponse
    {
        $params = $this->signParams($keyPair, [
            'symbol' => $symbol,
            'origClientOrderId' => $uuid,
        ]);

        $response = $this->authRequest($keyPair)->get('/api/v3/order', $params);

        $response = new BinanceResponse($response);

        if ($response->failed()) {
            throw new BinanceRequestException($response);
        }

        return $response;
    }
}
