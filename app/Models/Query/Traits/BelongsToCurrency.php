<?php

namespace App\Models\Query\Traits;

use App\Models\Currency;
use App\Models\Query\BaseQuery;

/**
 * @mixin BaseQuery
 */
trait BelongsToCurrency
{
    public function ofCurrency(Currency|int $currency): static
    {
        return $this->ofCurrencyId($currency instanceof Currency ? $currency->id : $currency);
    }

    public function ofCurrencyId(int $currencyId): static
    {
        return $this->where('currency_id', '=', $currencyId);
    }
}
