<?php

namespace Domain\CoinMarketCap\Http;

use Domain\CoinMarketCap\Http\Concerns\CoinMarketCapClientInterface;
use Illuminate\Http\Client\Response;

class CoinMarketCapClientMock implements CoinMarketCapClientInterface
{
    public function latestByCap(): Response
    {
        return response_from_client(data: $this->mockData('latest-by-cap.json'));
    }

    private function mockData(string $filename): array
    {
        $json = file_get_contents(
            filename: domain_path('CoinMarketCap', "Resources/mocks/{$filename}")
        );

        return json_decode($json, associative: true);
    }
}
