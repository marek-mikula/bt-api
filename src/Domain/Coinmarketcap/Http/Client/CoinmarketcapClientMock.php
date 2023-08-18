<?php

namespace Domain\Coinmarketcap\Http\Client;

use Domain\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CoinmarketcapClientMock implements CoinmarketcapClientInterface
{
    public function latestByCap(int $page = 1, int $perPage = 100): Response
    {
        // transform the $page and $perPage parameters
        // to numbers which match with the numbers in
        // the names of the mock files, so we can
        // grab the correct mock file

        $from = round_down_to_nearest_multiple(($page - 1) * $perPage, multiple: 1_000) + 1;
        $to = round_up_to_nearest_multiple($page * $perPage, multiple: 1_000);

        $mockFilePath = $to > 10_000 ? 'latest-by-cap/empty.json' : "latest-by-cap/{$from}_{$to}.json";

        // normalize the page to the current file

        $page = $page - (($from - 1) / $perPage);

        // retrieve the data as array from the json mock file

        $data = $this->mockData($mockFilePath);

        // take only the correct portion of the file
        // which should be on the current page

        $data['data'] = collect($data['data'])
            ->splice(($page - 1) * $perPage, $perPage)
            ->all();

        return response_from_client(data: $data);
    }

    public function coinMetadata(Collection $ids): Response
    {
        // cast collection to array

        $ids = $ids->all();

        // firstly retrieve the map od IDs, so we know
        // in which file each ID lays

        $map = $this->mockData('coin-metadata/map.json');

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

        $responseData = $this->mockData('coin-metadata/empty.json');

        foreach ($map as $file => $fileIds) {
            // retrieve data from current file
            $data = $this->mockData("coin-metadata/{$file}")['data'];

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

    public function coinMetadataByTicker(Collection $tickers): Response
    {
        $map = $this->mockData('map-symbol-to-id.json');

        // map tickers/symbols to collection of IDs
        // map only those that exists

        $ids = $tickers
            ->filter(static fn (string $ticker): bool => array_key_exists($ticker, $map))
            ->map(static fn (string $ticker): int => $map[$ticker]);

        return $this->coinMetadata($ids);
    }

    public function latestGlobalMetrics(): Response
    {
        return response_from_client(data: $this->mockData('latest-global-metrics.json'));
    }

    public function keyInfo(): Response
    {
        return response_from_client(data: $this->mockData('key-info.json'));
    }

    private function mockData(string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: domain_path('Coinmarketcap', "Resources/mocks/{$path}")
        );

        return json_decode(json: $json, associative: true);
    }
}
