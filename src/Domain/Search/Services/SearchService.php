<?php

namespace Domain\Search\Services;

use Domain\Coinmarketcap\Http\CoinmarketcapApi;
use Domain\Coinranking\Http\CoinrankingApi;
use Domain\Search\Data\SearchResult;
use Illuminate\Support\Collection;

class SearchService
{
    public function __construct(
        private readonly CoinmarketcapApi $coinmarketcapApi,
        private readonly CoinrankingApi $coinrankingApi,
    ) {
    }

    /**
     * @return Collection<SearchResult>
     */
    public function search(string $query): Collection
    {
        $coins = $this->coinrankingApi->search($query)
            ->collect('data.coins');

        // no search results found

        if ($coins->isEmpty()) {
            return collect();
        }

        $symbols = $coins->pluck('symbol')->all();

        $metadata = $this->coinmarketcapApi->coinMetadataByTicker($symbols)
            ->collect('data')
            ->keyBy('symbol');

        // we probably haven't found metadata for each
        // of the coins returned from Coinranking API
        // => remove those coins

        if ($coins->count() !== $metadata->count()) {
            $coins = $coins->filter(function (array $coin) use ($metadata): bool {
                return $metadata->has($coin['symbol']);
            });
        }

        return $coins->map(function (array $coin) use ($metadata): SearchResult {
            $meta = $metadata->get($coin['symbol']);

            return SearchResult::from([
                'id' => (int) $meta['id'],
                'name' => (string) $meta['name'],
                'symbol' => (string) $meta['symbol'],
                'description' => (string) $meta['description'],
                'logo' => (string) $meta['logo'],
                'urls' => $meta['urls'],
                'price' => floatval($coin['price']),
                'priceCurrency' => 'USD',
            ]);
        });
    }
}
