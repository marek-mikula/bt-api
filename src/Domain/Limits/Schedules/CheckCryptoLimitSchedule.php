<?php

namespace Domain\Limits\Schedules;

use App\Models\Limits;
use App\Schedules\BaseSchedule;
use Domain\Limits\Enums\LimitsNotificationPeriodEnum;
use Domain\Limits\Jobs\CheckCryptoLimitJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CheckCryptoLimitSchedule extends BaseSchedule
{
    public function __invoke(LimitsNotificationPeriodEnum $period): void
    {
        $query = Limits::query()
            ->where('cryptocurrency_enabled', '=', 1)
            ->where('cryptocurrency_period', '=', $period->value)
            ->where(function (Builder $query): void {
                $query
                    ->whereNotNull('cryptocurrency_min')
                    ->orWhereNotNull('cryptocurrency_max');
            });

        // no limits to check
        if (! $query->exists()) {
            return;
        }

        $query
            ->pluck('id')
            ->chunk(50)
            ->each(function (Collection $chunk): void {
                // dispatch job for each chunk
                CheckCryptoLimitJob::dispatch($chunk->all());
            });
    }
}
