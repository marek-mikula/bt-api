<?php

namespace App\Models\Query;

use App\Models\Order;
use App\Models\Query\Traits\BelongsToUser;

/**
 * @see Order
 */
class OrderQuery extends BaseQuery
{
    use BelongsToUser;
}
