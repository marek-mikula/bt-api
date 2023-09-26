<?php

namespace App\Repositories\Cryptocurrency;

use Illuminate\Pagination\LengthAwarePaginator;

interface CurrencyRepositoryInterface
{
    public function cryptocurrencies(int $page, int $perPage = 50): LengthAwarePaginator;
}
