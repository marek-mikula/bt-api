<?php

namespace App\Repositories\Order;

use App\Models\CurrencyPair;
use App\Models\Order;
use App\Models\User;

class OrderRepository implements OrderRepositoryInterface
{
    public function create(array $data): Order
    {
        /** @var Order $order */
        $order = Order::query()->create($data);

        return $order;
    }

    public function getDailyOrderCount(User $user): int
    {
        $from = now()->startOfDay();
        $to = now()->endOfDay();

        return Order::query()
            ->ofUser($user)
            ->where('created_at', '>=', $from->toDateTimeString())
            ->where('created_at', '<=', $to->toDateTimeString())
            ->count();
    }

    public function getWeeklyOrderCount(User $user): int
    {
        $from = now()->startOfWeek()->startOfDay();
        $to = now()->endOfWeek()->endOfDay();

        return Order::query()
            ->ofUser($user)
            ->where('created_at', '>=', $from->toDateTimeString())
            ->where('created_at', '<=', $to->toDateTimeString())
            ->count();
    }

    public function getMonthlyOrderCount(User $user): int
    {
        $from = now()->startOfMonth()->startOfDay();
        $to = now()->endOfMonth()->endOfDay();

        return Order::query()
            ->ofUser($user)
            ->where('created_at', '>=', $from->toDateTimeString())
            ->where('created_at', '<=', $to->toDateTimeString())
            ->count();
    }

    public function sumWaitingOrderQuote(User $user, CurrencyPair $pair): float
    {
        $buyOrders = Order::query()
            ->ofUser($user)
            ->ofCurrencyPair($pair)
            ->waiting()
            ->buy()
            ->sum('quote_quantity');

        $buyOrders = floatval($buyOrders);

        $sellOrders = Order::query()
            ->ofUser($user)
            ->ofCurrencyPair($pair)
            ->waiting()
            ->sell()
            ->sum('quote_quantity');

        $sellOrders = floatval($sellOrders);

        return $sellOrders - $buyOrders;
    }

    public function sumWaitingOrderBase(User $user, CurrencyPair $pair): float
    {
        $buyOrders = Order::query()
            ->ofUser($user)
            ->ofCurrencyPair($pair)
            ->waiting()
            ->buy()
            ->sum('base_quantity');

        $buyOrders = floatval($buyOrders);

        $sellOrders = Order::query()
            ->ofUser($user)
            ->ofCurrencyPair($pair)
            ->waiting()
            ->sell()
            ->sum('base_quantity');

        $sellOrders = floatval($sellOrders);

        return $buyOrders - $sellOrders;
    }
}
