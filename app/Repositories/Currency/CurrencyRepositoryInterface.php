<?php

namespace App\Repositories\Currency;

use App\Models\Currency;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CurrencyRepositoryInterface
{
    public function cryptocurrenciesIndex(int $page, int $perPage = 50): LengthAwarePaginator;

    /**
     * @return Collection<Currency>
     */
    public function topCryptocurrencies(int $count = 5): Collection;
}
