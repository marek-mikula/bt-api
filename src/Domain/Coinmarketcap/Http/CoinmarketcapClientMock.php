<?php

namespace Domain\Coinmarketcap\Http;

use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CoinmarketcapClientMock implements CoinmarketcapClientInterface
{
    public function latestByCap(): Response
    {
        return response_from_client(data: $this->mockData('latest-by-cap.json'));
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
