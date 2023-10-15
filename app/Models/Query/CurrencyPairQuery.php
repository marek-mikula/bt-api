<?php

namespace App\Models\Query;

use App\Models\CurrencyPair;
use App\Models\Query\Traits\BelongsToCurrency;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @see CurrencyPair
 */
class CurrencyPairQuery extends BaseQuery
{
    use BelongsToCurrency;

    public function ofSymbol(string $symbol): CurrencyPairQuery
    {
        return $this->ofSymbols(Arr::wrap($symbol));
    }

    public function ofSymbols(array|Collection $symbols): CurrencyPairQuery
    {
        $symbols = ($symbols instanceof Collection ? $symbols : collect($symbols))
            ->map([Str::class, 'upper']);

        return $this->whereIn('symbol', $symbols->all());
    }
}
