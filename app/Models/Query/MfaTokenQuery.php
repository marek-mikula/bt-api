<?php

namespace App\Models\Query;

use App\Models\MfaToken;
use App\Models\Query\Traits\BelongsToUser;

/**
 * @see MfaToken
 */
class MfaTokenQuery extends BaseQuery
{
    use BelongsToUser;

    public function valid(): MfaTokenQuery
    {
        return $this->where(function (MfaTokenQuery $query): void {
            $query
                ->where('valid_until', '>', now()->toDateTimeString())
                ->whereNull('invalidated_at');
        });
    }
}
