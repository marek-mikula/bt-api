<?php

namespace App\Repositories\Currency;

use App\Models\Currency;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function cryptocurrenciesIndex(int $page, int $perPage = 50): LengthAwarePaginator
    {
        return Currency::query()
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
