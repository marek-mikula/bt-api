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
            fileEmpty: 'empty.json'
        );

        return response_from_client(data: $data);
    }

    public function coinMetadata(Collection $ids): Response
    {
        // cast collection to array

        $ids = $ids->all();

        // firstly retrieve the map od IDs, so we know
        // in which file each ID lays

        $map = $this->mockData('Coinmarketcap', 'metadata/map.json');

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

        $responseData = $this->mockData('Coinmarketcap', 'empty.json');

        foreach ($map as $file => $fileIds) {
            // retrieve data from current file
            $data = $this->mockData('Coinmarketcap', "metadata/{$file}")['data'];

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

    public function map(int $page = 1, int $perPage = 100, Collection $symbols = null): Response
    {
        // we are searching for specific symbols
        // => ignore pagination and look only for these
        //    symbols

        if ($symbols?->count() > 0) {
            // cast collection to array

            $symbols = $symbols->all();

            // firstly retrieve the map od symbols, so we know
            // in which file each symbol lays

            $map = $this->mockData('Coinmarketcap', 'map/map.json');

            // search through the map of symbols and keep only
            // those files where we need to look for the map
            // object for each given symbol

            foreach ($map as $file => $fileSymbols) {
                $map[$file] = array_intersect($fileSymbols, $symbols);

                // unset file because there are no map data we need to look for
                if (empty($map[$file])) {
                    unset($map[$file]);
                }
            }

            // load empty response data JSON
            // => we will fill it with data from each file we need to look through

            $responseData = $this->mockData('Coinmarketcap', 'empty.json');

            foreach ($map as $file => $fileSymbols) {
                // retrieve data from current file
                $data = $this->mockData('Coinmarketcap', "map/{$file}")['data'];

                // filter only those items we are looking for
                // based on given symbols in current file
                $data = Arr::where($data, function (array $item) use ($fileSymbols): bool {
                    return in_array($item['symbol'], $fileSymbols);
                });

                // merge arrays

                $responseData['data'] = array_merge(
                    array_values($responseData['data']),
                    array_values($data)
                );
            }

            // remove indexes to be sure
            $responseData['data'] = array_values($responseData['data']);

            return response_from_client(data: $responseData);
        }

        $data = $this->mockPagination(
            domain: 'Coinmarketcap',
            page: $page,
            perPage: $perPage,
            fileStep: 5_000,
            fileMax: 25_000,
            fileData: 'map/%s_%s.json',
            fileEmpty: 'empty.json'
        );

        return response_from_client(data: $data);
    }

    public function mapFiat(int $page = 1, int $perPage = 100): Response
    {
        $data = $this->mockPagination(
            domain: 'Coinmarketcap',
            page: $page,
            perPage: $perPage,
            fileStep: 5_000,
            fileMax: 5_000,
            fileData: 'map-fiat/%s_%s.json',
            fileEmpty: 'empty.json'
        );

        return response_from_client(data: $data);
    }

    public function quotes(Collection $ids): Response
    {
        // cast collection to array

        $ids = $ids->all();

        // firstly retrieve the map od ids, so we know
        // in which file each id lays

        $map = $this->mockData('Coinmarketcap', 'quotes/map.json');

        // search through the map of ids and keep only
        // those files where we need to look for the map
        // object for each given id

        foreach ($map as $file => $fileIds) {
            $map[$file] = array_intersect($fileIds, $ids);

            // unset file because there are no quotes we need to look for
            if (empty($map[$file])) {
                unset($map[$file]);
            }
        }

        // load empty response data JSON
        // => we will fill it with data from each file we need to look through

        $responseData = $this->mockData('Coinmarketcap', 'empty.json');

        foreach ($map as $file => $fileIds) {
            // retrieve data from current file
            $data = $this->mockData('Coinmarketcap', "quotes/{$file}")['data'];

            // filter only those items we are looking for
            // based on given ids in current file
            $data = Arr::where($data, function (array $item) use ($fileIds): bool {
                return in_array($item['id'], $fileIds);
            });

            // merge arrays

            $responseData['data'] += $data;
        }

        return response_from_client(data: $responseData);
    }

    public function keyInfo(): Response
    {
        return response_from_client(data: $this->mockData('Coinmarketcap', 'key-info.json'));
    }
}
