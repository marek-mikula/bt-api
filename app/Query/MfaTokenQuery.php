<?php

namespace App\Query;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class MfaTokenQuery extends Builder
{
    public function valid(): MfaTokenQuery
    {
        return $this->where(function (MfaTokenQuery $query): void {
            $query
                ->where('invalidated', '=', false)
                ->where('valid_until', '>', Carbon::now()->toDateTimeString())
                ->whereNull('invalidated_at');
        });
    }
}
