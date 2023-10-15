<?php

namespace App\Models\Query;

use App\Models\Asset;
use App\Models\Query\Traits\BelongsToCurrency;
use App\Models\Query\Traits\BelongsToUser;

/**
 * @see Asset
 */
class AssetQuery extends BaseQuery
{
    use BelongsToUser;
    use BelongsToCurrency;
}
