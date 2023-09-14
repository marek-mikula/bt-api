<?php

namespace Domain\Limits\Jobs;

use App\Enums\QueueEnum;
use App\Jobs\BaseJob;
use App\Models\User;
use Domain\Limits\Data\LimitQuoteData;
use Domain\Limits\Enums\MarketCapCategoryEnum;
use Domain\Limits\Notifications\LimitsMarketCapNotification;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CheckMarketCapLimitJob extends BaseJob
{
    use Batchable;

    /**
     * @param  non-empty-list<int>  $limitIds
     */
    public function __construct(
        private readonly array $limitIds
    ) {
        $this->onQueue(QueueEnum::LIMITS->value);
    }

    public function handle(): void
    {
        if (empty($this->limitIds)) {
            return;
        }

        /** @var Collection<LimitQuoteData>|null $quotes */
        $quotes = Cache::tags([
            'limits',
            'limits-quotes',
        ])->get('limits:quotes');

        if (! $quotes) {
            throw new Exception('Missing cached quotes!');
        }

        User::query()
            ->with([
                'limits',
                'assets' => function (HasMany $query): void {
                    // take only fiat currency
                    $query->whereHas('currency', function (Builder $query): void {
                        $query->where('is_fiat', '=', 0);
                    });
                },
                'assets.currency',
            ])
            ->whereHas('limits', function (Builder $query): void {
                $query->whereIn('id', $this->limitIds);
            })
            ->each(function (User $user) use ($quotes): void {
                $this->check($quotes, $user);
            }, 50);
    }

    /**
     * @param  Collection<LimitQuoteData>  $quotes
     */
    private function check(Collection $quotes, User $user): void
    {
        $limits = $user->loadMissing('limits')->limits;

        $percentages = [
            MarketCapCategoryEnum::MICRO->value => 0.0,
            MarketCapCategoryEnum::SMALL->value => 0.0,
            MarketCapCategoryEnum::MID->value => 0.0,
            MarketCapCategoryEnum::LARGE->value => 0.0,
            MarketCapCategoryEnum::MEGA->value => 0.0,
        ];

        // calculate the percentages of
        // each market cap category in
        // user's wallet

        foreach ($user->loadMissing('assets')->assets as $asset) {
            $currency = $asset->loadMissing('currency')->currency;

            // check if is fiat just to be sure

            if ($currency->is_fiat) {
                continue;
            }

            /** @var LimitQuoteData|null $quote */
            $quote = $quotes->get($currency->coinmarketcap_id);

            if (! $quote) {
                throw new Exception("Missing cached quotes for currency {$currency->symbol}.");
            }

            $percentages[$quote->getMarketCapCategory()->value] += ($asset->balance * $quote->price);
        }

        $totalBalance = collect($percentages)->sum();

        // now check all percentages if they are
        // in the correct percentage span

        foreach ($percentages as $category => $value) {
            // do not check the limit if it
            // is disabled

            if (! $limits->{"market_cap_{$category}_enabled"}) {
                continue;
            }

            // calculate the numbers the final value
            // should be between

            $between = [
                ((int) $limits->{"market_cap_{$category}"}) - $limits->market_cap_margin,
                ((int) $limits->{"market_cap_{$category}"}) + $limits->market_cap_margin,
            ];

            // calculate the percentage

            $percentage = ($value * 100) / $totalBalance;

            if ($percentage < $between[0] || $percentage > $between[1]) {
                $user->notify(new LimitsMarketCapNotification(
                    category: MarketCapCategoryEnum::from($category),
                    percentage: $percentage,
                    limitFrom: $between[0],
                    limitTo: $between[1],
                ));
            }
        }
    }
}
