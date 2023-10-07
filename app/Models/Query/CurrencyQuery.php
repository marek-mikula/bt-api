<?php

namespace App\Models\Query;

use App\Models\Currency;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @see Currency
 */
class CurrencyQuery extends BaseQuery
{
    public function fiat(): CurrencyQuery
    {
        return $this->where('is_fiat', '=', 1);
    }

    public function crypto(): CurrencyQuery
    {
        return $this->where('is_fiat', '=', 0);
    }

    public function ofSymbol(string $symbol): CurrencyQuery
    {
        return $this->ofSymbols(Arr::wrap($symbol));
    }

    public function ofSymbols(array|Collection $symbols): CurrencyQuery
    {
        $symbols = ($symbols instanceof Collection ? $symbols : collect($symbols))
            ->map([Str::class, 'upper']);

        return $this->whereIn('symbol', $symbols->all());
    }
}
