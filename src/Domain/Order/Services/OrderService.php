<?php

namespace Domain\Order\Services;

use Apis\Binance\Data\OrderData;
use Apis\Binance\Http\BinanceApi;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Asset\AssetRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Domain\Cryptocurrency\Enums\OrderSideEnum;
use Domain\Cryptocurrency\Enums\OrderStatusEnum;
use Domain\Order\Http\Requests\Data\OrderRequestData;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly OrderBuyValidator $buyValidator,
        private readonly BinanceApi $binanceApi,
    ) {
    }

    public function buy(User $user, OrderRequestData $data): Order
    {
        $uuid = Str::uuid()->toString();

        $order = OrderData::from([
            'uuid' => $uuid,
            'quantity' => $data->quantity,
            'pair' => $data->pair,
            'side' => OrderSideEnum::BUY,
        ]);

        $this->buyValidator->validate(
            user: $user,
            order: $order,
            ignoreLimitsValidation: $data->ignoreLimitsValidation
        );

        $response = $this->binanceApi->spot->placeOrder($user->getKeyPair(), $order);

        $order = $this->orderRepository->create([
            'binance_uuid' => (string) $response->json('clientOrderId'),
            'binance_id' => (int) $response->json('orderId'),
            'user_id' => $user->id,
            'pair_id' => $data->pair->id,
            'side' => (string) $response->json('side'),
            'status' => (string) $response->json('status'),
            'base_quantity' => floatval($response->json('executedQty')),
            'quote_quantity' => floatval($response->json('cummulativeQuoteQty')),
            'price' => floatval($response->json('fills.0.price')),
        ]);

        $order->loadMissing([
            'pair',
        ]);

        // update the balances if the order got filled
        // instantly

        if ($order->status === OrderStatusEnum::FILLED) {
            $this->assetRepository->updateBalanceByOrder($order);
        }

        return $order;
    }

    public function sell(User $user, OrderRequestData $data): Order
    {
        // todo
    }
}
