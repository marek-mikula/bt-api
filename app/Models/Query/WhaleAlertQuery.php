<?php

namespace App\Models\Query;

use App\Models\Query\Traits\BelongsToCurrency;
use App\Models\WhaleAlert;

/**
 * @see WhaleAlert
 */
class WhaleAlertQuery extends BaseQuery
{
    use BelongsToCurrency;
}
