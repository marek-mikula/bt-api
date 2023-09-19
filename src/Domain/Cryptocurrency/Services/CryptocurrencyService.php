<?php

namespace Domain\Cryptocurrency\Services;

use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use Domain\Cryptocurrency\Data\Cryptocurrency;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CryptocurrencyService
{
    public function __construct(
        private readonly CoinmarketcapApi $coinmarketcapApi,
    ) {
    }

    public function getDataForIndex(int $page, int $perPage = 100): Collection
    {
        if ($page < 1) {
            $page = 1;
        }

        $data = $this->coinmarketcapApi->latestByCap($page, $perPage);

        $ids = $data->collect('data')
            ->pluck('id');

        $metadata = $this->coinmarketcapApi->coinMetadata($ids->all())
            ->collect('data');

        return collect(Arr::get($data, 'data', []))
            ->map(function (array $item) use ($metadata): Cryptocurrency {
                $currency = (string) collect($item['quote'])->keys()->first();

                $itemMetadata = $metadata->get((int) $item['id'], '');

                return Cryptocurrency::from([
                    'id' => (int) $item['id'],
                    'name' => (string) $item['name'],
                    'symbol' => (string) $item['symbol'],
                    'iconUrl' => (string) ($itemMetadata['logo'] ?? ''),
                    'infiniteSupply' => (bool) $item['infinite_supply'],
                    'totalSupply' => floatval($item['total_supply']),
                    'circulatingSupply' => floatval($item['circulating_supply']),
                    'maxSupply' => (int) $item['max_supply'],
                    'price' => floatval($item['quote'][$currency]['price']),
                    'priceChange1h' => floatval($item['quote'][$currency]['percent_change_1h']) / 100,
                    'priceChange24h' => floatval($item['quote'][$currency]['percent_change_24h']) / 100,
                    'priceChange7d' => floatval($item['quote'][$currency]['percent_change_7d']) / 100,
                    'priceChange30d' => floatval($item['quote'][$currency]['percent_change_30d']) / 100,
                    'priceChange60d' => floatval($item['quote'][$currency]['percent_change_60d']) / 100,
                    'priceChange90d' => floatval($item['quote'][$currency]['percent_change_90d']) / 100,
                    'marketCap' => floatval($item['quote'][$currency]['market_cap']),
                    'volume24h' => floatval($item['quote'][$currency]['volume_24h']),
                    'volumeChange24h' => floatval($item['quote'][$currency]['volume_change_24h']) / 100,
                    'currency' => $currency,
                ]);
            });
    }
}
