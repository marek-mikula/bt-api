<?php

namespace App\Models\Query\Traits;

use App\Models\Currency;
use App\Models\Query\BaseQuery;

/**
 * @mixin BaseQuery
 */
trait BelongsToCurrency
{
    public function ofCurrency(Currency|int $currency, string $column = 'currency_id'): static
    {
        return $this->ofCurrencyId($currency instanceof Currency ? $currency->id : $currency, $column);
    }

    public function ofCurrencyId(int $currencyId, string $column = 'currency_id'): static
    {
        return $this->where($column, '=', $currencyId);
    }
}
