<?php

namespace Domain\Search\Services;

use App\Models\Currency;
use Domain\Coinmarketcap\Http\CoinmarketcapApi;
use Domain\Search\Data\SearchResult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SearchService
{
    public function __construct(
        private readonly CoinmarketcapApi $coinmarketcapApi,
    ) {
    }

    /**
     * @return Collection<SearchResult>
     */
    public function search(string $query): Collection
    {
        $currencies = Currency::query()
            ->where('is_fiat', '=', 0)
            ->where(function (Builder $q) use ($query): void {
                $q
                    ->where('name', 'like', "%{$query}%")
                    ->orWhere('meta->description', 'like', "%{$query}%")
                    ->orWhere('symbol', '=', $query);
            })
            ->get();

        // no search results found

        if ($currencies->isEmpty()) {
            return collect();
        }

        $ids = $currencies->pluck('coinmarketcap_id');

        // retrieve current quotes for found
        // currencies

        $quotes = $this->coinmarketcapApi->quotes($ids->all())
            ->collect('data');

        return $currencies->map(function (Currency $currency) use ($quotes): SearchResult {
            /** @var array $quote */
            $quote = $quotes->get($currency->coinmarketcap_id);

            $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

            return SearchResult::from([
                'id' => $currency->id,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'description' => (string) Arr::get($currency->meta, 'description'),
                'logo' => (string) Arr::get($currency->meta, 'logo'),
                'urls' => Arr::get($currency->meta, 'urls', []),
                'price' => floatval($quote['quote'][$quoteCurrency]['price']),
                'priceCurrency' => $quoteCurrency,
            ]);
        });
    }
}
