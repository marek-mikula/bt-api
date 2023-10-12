<?php

namespace App\Models\Query\Traits;

use App\Models\CurrencyPair;
use App\Models\Query\BaseQuery;

/**
 * @mixin BaseQuery
 */
trait BelongsToCurrencyPair
{
    public function ofCurrencyPair(CurrencyPair|int $pair, string $column = 'pair_id'): static
    {
        return $this->ofCurrencyPairId($pair instanceof CurrencyPair ? $pair->id : $pair, $column);
    }

    public function ofCurrencyPairId(int $currencyPairId, string $column = 'pair_id'): static
    {
        return $this->where($column, '=', $currencyPairId);
    }
}
