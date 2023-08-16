<?php

namespace Domain\Coinmarketcap\Http;

use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CoinmarketcapClientMock implements CoinmarketcapClientInterface
{
    public function latestByCap(int $page = 1, int $perPage = 100): Response
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Parameter $page must be greater or equal to 1.');
        }

        if (($perPage % 5) !== 0) {
            throw new InvalidArgumentException('Parameter $perPage must be a multiple of 5');
        }

        if ($perPage > 200) {
            throw new InvalidArgumentException('Parameter $perPage must be less or equal to 200');
        }

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

    public function coinMetadata(int|array $id): Response
    {
        $id = collect(Arr::wrap($id));

        if ($id->isEmpty()) {
            throw new InvalidArgumentException('Cannot get metadata for no tokens.');
        }

        if ($id->count() > 200) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 200.');
        }

        // cast collection to array and make sure the
        // items are integers

        $id = $id->map('intval')->all();

        // firstly retrieve the map od IDs, so we know
        // in which file each ID lays

        $map = $this->mockData('coin-metadata/map.json');

        // search through the map of IDs and keep only
        // those files where we need to look for the metadata
        // for each given currency ID

        foreach ($map as $file => $ids) {
            $map[$file] = array_intersect($ids, $id);

            // unset file because there are no metadata we need to look for
            if (empty($map[$file])) {
                unset($map[$file]);
            }
        }

        // load empty response data JSON
        // => we will fill it with data from each file we need to look through

        $responseData = $this->mockData('coin-metadata/empty.json');

        foreach ($map as $file => $ids) {
            // retrieve data from current file
            $data = $this->mockData("coin-metadata/{$file}")['data'];

            // filter only those items we are looking for
            // based on given IDs in current file
            $data = Arr::where($data, function (array $item) use ($ids): bool {
                return in_array($item['id'], $ids);
            });

            // concat arrays, use addition, array should be having
            // different and unique keys

            $responseData['data'] += $data;
        }

        return response_from_client(data: $responseData);
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
