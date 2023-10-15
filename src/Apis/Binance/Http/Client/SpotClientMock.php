<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Data\OrderData;
use Apis\Binance\Http\BinanceApi;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;
use App\Models\Order;
use App\Traits\MocksData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

        // fetch fake price

        /** @var BinanceApi $api */
        $api = app(BinanceApi::class);

        $response = $api->marketData->symbolPrice($order->pair->symbol);

        $price = floatval($response->json('price'));

        // replace values

        $data['symbol'] = $order->pair->symbol;
        $data['clientOrderId'] = $order->uuid;
        $data['transactTime'] = $timestampMs;
        $data['origQty'] = $order->quantity;
        $data['executedQty'] = $order->quantity;
        $data['cummulativeQuoteQty'] = $order->quantity * $price;
        $data['workingTime'] = $timestampMs;
        $data['fills'][0]['qty'] = $order->quantity;
        $data['fills'][0]['price'] = $price;
        $data['side'] = $order->side->name;

        return new BinanceResponse(response_from_client(data: $data));
    }

    public function order(KeyPairData $keyPair, string $symbol, string $uuid): BinanceResponse
    {
        /** @var Order|null $order */
        $order = Order::query()
            ->with('pair')
            ->whereHas('pair', static function (BelongsTo $query) use ($symbol): void {
                $query->where('symbol', '=', $symbol);
            })
            ->ofBinanceUuid($uuid)
            ->first();

        if (! $order) {
            return new BinanceResponse(response_from_client(status: 404));
        }

        $data = $this->mockData('Binance', 'spot/get-order.json');

        $timestampMs = now()->getTimestampMs();

        // replace values

        $data['symbol'] = $order->pair->symbol;
        $data['orderId'] = $order->binance_id;
        $data['clientOrderId'] = $order->binance_uuid;
        $data['price'] = $order->price;
        $data['origQty'] = $order->base_quantity;
        $data['executedQty'] = $order->base_quantity;
        $data['cummulativeQuoteQty'] = $order->quote_quantity;
        $data['status'] = $order->status->name;
        $data['side'] = $order->side->name;
        $data['time'] = $timestampMs;
        $data['updateTime'] = $timestampMs;
        $data['workingTime'] = $timestampMs;
        $data['origQuoteOrderQty'] = $order->quote_quantity;

        return new BinanceResponse(response_from_client(data: $data));
    }
}
