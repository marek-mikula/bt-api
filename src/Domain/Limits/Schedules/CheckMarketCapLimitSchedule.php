<?php

namespace Domain\Limits\Schedules;

use App\Models\Limits;
use App\Schedules\BaseSchedule;
use Domain\Limits\Enums\LimitsNotificationPeriodEnum;
use Domain\Limits\Jobs\CheckMarketCapLimitJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CheckMarketCapLimitSchedule extends BaseSchedule
{
    public function __invoke(LimitsNotificationPeriodEnum $period): void
    {
        $query = Limits::query()
            ->where('market_cap_enabled', '=', 1)
            ->where('market_cap_period', '=', $period->value)
            ->whereNotNull('market_cap_margin')
            ->where(function (Builder $query): void {
                $query
                    ->where(function (Builder $query): void {
                        $query->where('market_cap_micro_enabled', '=', 1)
                            ->whereNotNull('market_cap_micro');
                    })
                    ->orWhere(function (Builder $query): void {
                        $query->where('market_cap_small_enabled', '=', 1)
                            ->whereNotNull('market_cap_small');
                    })
                    ->orWhere(function (Builder $query): void {
                        $query->where('market_cap_mid_enabled', '=', 1)
                            ->whereNotNull('market_cap_mid');
                    })
                    ->orWhere(function (Builder $query): void {
                        $query->where('market_cap_large_enabled', '=', 1)
                            ->whereNotNull('market_cap_large');
                    })
                    ->orWhere(function (Builder $query): void {
                        $query->where('market_cap_mega_enabled', '=', 1)
                            ->whereNotNull('market_cap_mega');
                    });
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
                CheckMarketCapLimitJob::dispatch($chunk->all());
            });
    }
}
