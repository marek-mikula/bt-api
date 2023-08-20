<?php

namespace Domain\Cryptocurrency\Services;

use Domain\Coinmarketcap\Http\CoinmarketcapApi;
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
                    'price' => floatval($item['quote'][$currency]['price']),
                    'priceCurrency' => $currency,
                    'iconUrl' => (string) ($itemMetadata['logo'] ?? ''),
                ]);
            });
    }
}
