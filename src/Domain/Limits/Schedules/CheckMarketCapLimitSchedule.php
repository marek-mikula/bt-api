<?php

namespace Domain\Limits\Schedules;

use App\Enums\CurrencyStateEnum;
use App\Enums\QueueEnum;
use App\Models\Currency;
use App\Models\Limits;
use App\Schedules\BaseSchedule;
use Domain\Coinmarketcap\Http\CoinmarketcapApi;
use Domain\Limits\Data\LimitQuoteData;
use Domain\Limits\Enums\LimitsNotificationPeriodEnum;
use Domain\Limits\Jobs\CheckMarketCapLimitJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

class CheckMarketCapLimitSchedule extends BaseSchedule
{
    public function __invoke(
        LimitsNotificationPeriodEnum $period,
        CoinmarketcapApi $coinmarketcapApi,
    ): void {
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

        // load the quotes to cache if needed

        if (! Cache::tags(['limits', 'limits-quotes'])->has('limits:quotes')) {
            $this->loadQuotes($query, $coinmarketcapApi);
        }

        // pluck the limit IDs, chunk the
        // collection and transform it to
        // array of jobs

        $batch = $query
            ->pluck('id')
            ->chunk(100)
            ->map(static function (Collection $chunk) use (&$batch): CheckMarketCapLimitJob {
                return new CheckMarketCapLimitJob($chunk->all());
            })
            ->all();

        // dispatch all the jobs as a batch

        Bus::batch($batch)
            ->name('Check market cap limits')
            ->onQueue(QueueEnum::LIMITS->value)
            ->dispatch();
    }

    private function loadQuotes(Builder $limitQuery, CoinmarketcapApi $coinmarketcapApi): void
    {
        // first fetch all the ids
        // we will need the quotes for

        $ids = Currency::query()
            ->where('state', '=', CurrencyStateEnum::SUPPORTED->value)
            ->where('is_fiat', '=', 0)
            ->whereHas('assets', function (Builder $q) use ($limitQuery): void {
                $q->whereIn('user_id', $limitQuery->clone()->select('user_id'));
            })
            ->pluck('coinmarketcap_id');

        // now fetch all the needed
        // quotes from coinmarketcap
        // and transform the result
        // into data object

        $quotes = $coinmarketcapApi->quotes($ids->all())
            ->collect('data')
            ->map(function (array $item): LimitQuoteData {
                $currency = (string) collect($item['quote'])->keys()->first();

                return LimitQuoteData::from([
                    'currency' => $currency,
                    'price' => floatval($item['quote'][$currency]['price']),
                    'marketCap' => floatval($item['quote'][$currency]['market_cap']),
                ]);
            });

        // save the collection of quotes to cache
        // till the end of the day so each schedule
        // for limits (daily/weekly/monthly) can use
        // it

        Cache::tags([
            'limits',
            'limits-quotes',
        ])->put('limits:quotes', $quotes, now()->endOfDay());
    }
}
