<?php

namespace App\Models\Query;

use Illuminate\Database\Eloquent\Builder;

class MfaTokenQuery extends Builder
{
    public function valid(): MfaTokenQuery
    {
        return $this->where(function (MfaTokenQuery $query): void {
            $query
                ->where('valid_until', '>', now()->toDateTimeString())
                ->whereNull('invalidated_at');
        });
    }
}
