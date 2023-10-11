<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Data\OrderData;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;
use App\Traits\MocksData;

class SpotClientMock implements SpotClientInterface
{
    use MocksData;

    public function account(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'spot/account.json'));

        return new BinanceResponse($response);
    }

    public function placeOrder(KeyPairData $keyPair, OrderData $order): BinanceResponse
    {
        $data = $this->mockData('Binance', 'spot/place-order.json');

        // fake order data

        $timestampMs = now()->getTimestampMs();

        $data['symbol'] = $order->symbol;
        $data['clientOrderId'] = $order->uuid;
        $data['transactTime'] = $timestampMs;
        $data['origQty'] = $order->quantity;
        $data['executedQty'] = $order->quantity;
        $data['workingTime'] = $timestampMs;
        $data['fills'][0]['qty'] = $order->quantity;
        $data['side'] = $order->type->name;

        return new BinanceResponse(response_from_client(data: $data));
    }

    public function order(KeyPairData $keyPair, OrderData $order): BinanceResponse
    {
        $data = $this->mockData('Binance', 'spot/get-order.json');

        // fake order data

        $timestampMs = now()->getTimestampMs();

        $data['symbol'] = $order->symbol;
        $data['clientOrderId'] = $order->uuid;
        $data['time'] = $timestampMs;
        $data['updateTime'] = $timestampMs;
        $data['workingTime'] = $timestampMs;
        $data['origQty'] = (string) $order->quantity;
        $data['executedQty'] = (string) $order->quantity;
        $data['side'] = $order->type->name;

        return new BinanceResponse(response_from_client(data: $data));
    }
}
