<?php

namespace App\Repositories\Currency;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function cryptocurrenciesIndex(User $user, int $page, int $perPage = 50): LengthAwarePaginator
    {
        return Currency::query()
            ->with('assets', static function (HasMany $query) use ($user): void {
                $query->where('user_id', '=', $user->id);
            })
            ->crypto()
            ->orderBy('cmc_rank')
            ->paginate(
                perPage: $perPage,
                page: $page
            );
    }

    public function topCryptocurrencies(int $count = 5): Collection
    {
        return Currency::query()
            ->crypto()
            ->orderBy('cmc_rank')
            ->limit($count)
            ->get();
    }
}
