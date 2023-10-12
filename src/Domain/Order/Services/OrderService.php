<?php

namespace Domain\Order\Services;

use Apis\Binance\Data\OrderData;
use Apis\Binance\Http\BinanceApi;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Order\OrderRepositoryInterface;
use Domain\Cryptocurrency\Enums\OrderTypeEnum;
use Domain\Order\Http\Requests\Data\OrderRequestData;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderBuyValidator $orderValidator,
        private readonly BinanceApi $binanceApi,
    ) {
    }

    public function buy(User $user, OrderRequestData $data): Order
    {
        $uuid = Str::uuid()->toString();

        $order = OrderData::from([
            'uuid' => $uuid,
            'symbol' => $data->pair->symbol,
            'type' => OrderTypeEnum::BUY,
            'quantity' => $data->quantity,
            'quantityPrecision' => $data->pair->base_currency_precision,
        ]);

        $this->orderValidator->validate($user, $data->pair, $order);

        $response = $this->binanceApi->spot->placeOrder($user->getKeyPair(), $order);

        $order = $this->orderRepository->create([
            'binance_uuid' => $uuid,
            'user_id' => $user->id,
            'pair_id' => $data->pair->id,
            'type' => OrderTypeEnum::BUY,
            'status' => $response->json('status'),
            'quantity' => floatval($response->json('executedQty')),
            'quote_quantity' => floatval($response->json('cummulativeQuoteQty')),
            'price' => floatval($response->json('fills.0.price')),
        ]);

        $order->loadMissing([
            'pair',
        ]);

        return $order;
    }

    public function sell(User $user, OrderRequestData $data): Order
    {
        // todo
    }
}
