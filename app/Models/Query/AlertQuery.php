<?php

namespace App\Models\Query;

use App\Models\Alert;
use App\Models\Query\Traits\BelongsToUser;

/**
 * @see Alert
 */
class AlertQuery extends BaseQuery
{
    use BelongsToUser;
}
