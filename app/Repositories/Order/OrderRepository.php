<?php

namespace App\Repositories\Order;

use App\Models\Currency;
use App\Models\CurrencyPair;
use App\Models\Order;
use App\Models\Query\CurrencyPairQuery;
use App\Models\Query\OrderQuery;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function index(User $user, int $page, int $perPage = 50): LengthAwarePaginator
    {
        return Order::query()
            ->with([
                'pair',
                'pair.baseCurrency',
                'pair.quoteCurrency',
            ])
            ->ofUser($user)
            ->latest('id')
            ->paginate(
                perPage: $perPage,
                page: $page
            );
    }

    public function latest(User $user, int $count = 10, Currency $currency = null): Collection
    {
        return Order::query()
            ->with([
                'pair',
                'pair.baseCurrency',
                'pair.quoteCurrency',
            ])
            ->when($currency !== null, static function (OrderQuery $query) use ($currency): void {
                $query->whereHas('pair', static function (CurrencyPairQuery $query) use ($currency): void {
                    $query
                        ->where('base_currency_id', '=', $currency->id)
                        ->orWhere('quote_currency_id', '=', $currency->id);
                });
            })
            ->latest('id')
            ->limit($count)
            ->get();
    }

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
