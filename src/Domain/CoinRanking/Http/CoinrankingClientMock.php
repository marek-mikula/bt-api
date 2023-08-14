<?php

namespace Domain\CoinRanking\Http;

use Domain\CoinRanking\Http\Concerns\CoinrankingClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CoinrankingClientMock implements CoinrankingClientInterface
{
    public function search(string $query): Response
    {
        $data = $this->mockData('search.json');

        // try to find the query in provided json
        if (Arr::has($data, Str::lower($query))) {
            $data = $data[$query];
        } else {
            $data = $data['empty'];
        }

        return response_from_client(data: $data);
    }

    private function mockData(string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: domain_path('Coinranking', "Resources/mocks/{$path}")
        );

        return json_decode(json: $json, associative: true);
    }
}
