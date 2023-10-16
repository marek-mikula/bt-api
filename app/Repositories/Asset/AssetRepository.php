<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\Currency;
use App\Models\Order;
use App\Models\User;
use Domain\Order\Enums\OrderSideEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssetRepository implements AssetRepositoryInterface
{
    public function getByUser(User $user): Collection
    {
        return $user->assets()
            ->with('currency')
            ->orderBy(DB::raw('CASE WHEN currency_id IS NULL THEN 1 ELSE 0 END'))
            ->orderBy('balance', 'desc')
            ->get();
    }

    public function findByUserAndCurrency(User $user, Currency $currency): ?Asset
    {
        /** @var Asset|null $asset */
        $asset = $user->assets()
            ->with('currency')
            ->where('currency_id', '=', $currency->id)
            ->first();

        return $asset;
    }

    public function updateBalanceByOrder(Order $order): void
    {
        $pair = $order->loadMissing('pair')->pair;

        if ($order->side === OrderSideEnum::BUY) {
            Asset::query()
                ->ofUserId($order->user_id)
                ->ofCurrencyId($pair->base_currency_id)
                ->increment('balance', $order->base_quantity);

            Asset::query()
                ->ofUserId($order->user_id)
                ->ofCurrencyId($pair->quote_currency_id)
                ->decrement('balance', $order->quote_quantity);
        } else {
            Asset::query()
                ->ofUserId($order->user_id)
                ->ofCurrencyId($pair->base_currency_id)
                ->decrement('balance', $order->base_quantity);

            Asset::query()
                ->ofUserId($order->user_id)
                ->ofCurrencyId($pair->quote_currency_id)
                ->increment('balance', $order->quote_quantity);
        }
    }
}
