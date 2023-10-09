<?php

namespace App\Models\Query;

use App\Models\CurrencyPair;
use App\Models\Query\Traits\BelongsToCurrency;

/**
 * @see CurrencyPair
 */
class CurrencyPairQuery extends BaseQuery
{
    use BelongsToCurrency;
}
