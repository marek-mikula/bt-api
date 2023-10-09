<?php

namespace App\Repositories\Asset;

use App\Models\Asset;
use App\Models\Currency;
use App\Models\User;
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
}
