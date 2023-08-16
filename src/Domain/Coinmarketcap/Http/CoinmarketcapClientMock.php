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
            throw new InvalidArgumentException('Cannot get metadata for no cryptocurrency.');
        }

        if ($id->count() > 100) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 100.');
        }

        $data = $this->mockData('coin-metadata.json');

        // get only specific coin based on given ID
        // from the whole list from json file
        $data['data'] = Arr::where($data['data'], static function (array $coin) use ($id): bool {
            return $id->contains((int) $coin['id']);
        });

        // check that every ID has been retrieved from json mock file
        if (count($data['data']) !== $id->count()) {
            $invalidIds = $id
                ->diff(collect($data['data'])->pluck('id'))
                ->implode(', ');

            throw new InvalidArgumentException("Unknown ID/IDs [{$invalidIds}] given. No mock data found.");
        }

        return response_from_client(data: $data);
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
