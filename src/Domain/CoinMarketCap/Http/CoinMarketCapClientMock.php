<?php

namespace Domain\CoinMarketCap\Http;

use Domain\CoinMarketCap\Http\Concerns\CoinMarketCapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CoinMarketCapClientMock implements CoinMarketCapClientInterface
{
    public function latestByCap(): Response
    {
        return response_from_client(data: $this->mockData('latest-by-cap.json'));
    }

    public function coinMetadata(int $id): Response
    {
        $data = $this->mockData('coin-metadata.json');

        // get only specific coin based on given ID
        // from the whole list from json file
        $data['data'] = Arr::where($data['data'], static function (array $coin) use ($id): bool {
            return $coin['id'] === $id;
        });

        if (empty($data['data'])) {
            throw new InvalidArgumentException("Unknown ID [{$id}] given. No moc data found.");
        }

        return response_from_client(data: $data);
    }

    private function mockData(string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: domain_path('CoinMarketCap', "Resources/mocks/{$path}")
        );

        return json_decode($json, associative: true);
    }
}
