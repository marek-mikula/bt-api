<?php

namespace App\Repositories\Cryptocurrency;

use App\Models\Currency;
use Illuminate\Pagination\LengthAwarePaginator;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    public function cryptocurrencies(int $page, int $perPage = 50): LengthAwarePaginator
    {
        return Currency::query()
            ->where('is_fiat', '=', 0)
            ->orderBy('cmc_rank')
            ->paginate(
                perPage: $perPage,
                page: $page
            );
    }
}
