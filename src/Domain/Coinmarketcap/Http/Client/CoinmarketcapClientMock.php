<?php

namespace Domain\Coinmarketcap\Http\Client;

use App\Traits\MocksData;
use Domain\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CoinmarketcapClientMock implements CoinmarketcapClientInterface
{
    use MocksData;

    public function latestByCap(int $page = 1, int $perPage = 100): Response
    {
        $data = $this->mockPagination(
            domain: 'Coinmarketcap',
            page: $page,
            perPage: $perPage,
            fileStep: 1_000,
            fileMax: 10_000,
            fileData: 'latest-by-cap/%s_%s.json',
            fileEmpty: 'latest-by-cap/empty.json'
        );

        return response_from_client(data: $data);
    }

    public function coinMetadata(Collection $ids): Response
    {
        // cast collection to array

        $ids = $ids->all();

        // firstly retrieve the map od IDs, so we know
        // in which file each ID lays

        $map = $this->mockData('Coinmarketcap', 'coin-metadata/map.json');

        // search through the map of IDs and keep only
        // those files where we need to look for the metadata
        // for each given currency ID

        foreach ($map as $file => $fileIds) {
            $map[$file] = array_intersect($fileIds, $ids);

            // unset file because there are no metadata we need to look for
            if (empty($map[$file])) {
                unset($map[$file]);
            }
        }

        // load empty response data JSON
        // => we will fill it with data from each file we need to look through

        $responseData = $this->mockData('Coinmarketcap', 'coin-metadata/empty.json');

        foreach ($map as $file => $fileIds) {
            // retrieve data from current file
            $data = $this->mockData('Coinmarketcap', "coin-metadata/{$file}")['data'];

            // filter only those items we are looking for
            // based on given IDs in current file
            $data = Arr::where($data, function (array $item) use ($fileIds): bool {
                return in_array($item['id'], $fileIds);
            });

            // concat arrays, use addition, array should be having
            // different and unique keys

            $responseData['data'] += $data;
        }

        return response_from_client(data: $responseData);
    }

    public function coinMetadataBySymbol(Collection $symbols): Response
    {
        // map symbols to collection of Coinmarketcap IDs

        $ids = $this->map(symbols: $symbols)
            ->collect('data')
            ->pluck('id')
            ->map('intval')
            ->all();

        return $this->coinMetadata($ids);
    }

    public function latestGlobalMetrics(): Response
    {
        return response_from_client(data: $this->mockData('Coinmarketcap', 'latest-global-metrics.json'));
    }

    public function quotesLatest(Collection $ids): Response
    {
        return response_from_client();
    }

    public function quotesLatestBySymbol(Collection $symbols): Response
    {
        // map symbols to collection of Coinmarketcap IDs

        $ids = $this->map(symbols: $symbols)
            ->collect('data')
            ->pluck('id')
            ->map('intval')
            ->all();

        return $this->quotesLatest($ids);
    }

    public function map(int $page = 1, int $perPage = 100, Collection $symbols = null): Response
    {
        // we are searching for specific symbols
        // => ignore pagination and look only for these
        //    symbols

        if ($symbols && $symbols->count() > 0) {
            // load empty json
            $data = $this->mockData('Coinmarketcap', 'map/empty.json');

            // refresh the page param
            $page = 1;

            // search for those symbols until the
            // response is empty, or we have found
            // all the symbols
            do {
                $responseData = $this->map($page)->collect('data');

                // merge in only those items which
                // are contained in given symbols
                // collection
                $data['data'] = array_merge(
                    $data['data'],
                    $responseData->filter(function (array $item) use ($symbols): bool {
                        return $symbols->contains($item['symbol']);
                    })->all(),
                );

                $page++;
            } while (
                count($data['data']) !== $symbols->count() &&
                $responseData->count() > 0
            );

            // remove indexes to be sure
            $data['data'] = array_values($data['data']);

            return response_from_client(data: $data);
        }

        $data = $this->mockPagination(
            domain: 'Coinmarketcap',
            page: $page,
            perPage: $perPage,
            fileStep: 5_000,
            fileMax: 10_000,
            fileData: 'map/%s_%s.json',
            fileEmpty: 'map/empty.json'
        );

        return response_from_client(data: $data);
    }

    public function fiatMap(int $page = 1, int $perPage = 100): Response
    {
        $data = $this->mockPagination(
            domain: 'Coinmarketcap',
            page: $page,
            perPage: $perPage,
            fileStep: 5_000,
            fileMax: 5_000,
            fileData: 'fiat-map/%s_%s.json',
            fileEmpty: 'fiat-map/empty.json'
        );

        return response_from_client(data: $data);
    }

    public function keyInfo(): Response
    {
        return response_from_client(data: $this->mockData('Coinmarketcap', 'key-info.json'));
    }
}
