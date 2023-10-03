<?php

namespace App\Models\Query;

use App\Models\Limits;
use App\Models\Query\Traits\BelongsToUser;

/**
 * @see Limits
 */
class LimitsQuery extends BaseQuery
{
    use BelongsToUser;
}
