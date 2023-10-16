<?php

namespace Domain\Order\Services;

use Apis\Binance\Data\OrderData;
use Apis\Binance\Exceptions\BinanceBanException;
use Apis\Binance\Exceptions\BinanceLimitException;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceApi;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Asset\AssetRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Domain\Order\Enums\OrderStatusEnum;
use Domain\Order\Exceptions\OrderValidationException;
use Domain\Order\Http\Requests\Data\OrderRequestData;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly AssetRepositoryInterface $assetRepository,
        private readonly OrderValidator $validator,
        private readonly BinanceApi $binanceApi,
    ) {
    }

    /**
     * @throws BinanceBanException
     * @throws BinanceLimitException
     * @throws BinanceRequestException
     * @throws OrderValidationException
     */
    public function placeOrder(User $user, OrderRequestData $data): Order
    {
        $order = OrderData::from([
            'uuid' => Str::uuid()->toString(),
            'quantity' => $data->quantity,
            'pair' => $data->pair,
            'side' => $data->side,
        ]);

        $this->validator->validate(
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
}
