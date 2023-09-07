<?php

namespace Domain\OpenExchangeRates\Http\Client;

use Domain\OpenExchangeRates\Http\Client\Concerns\OpenExchangeRatesClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class OpenExchangeRatesClientMock implements OpenExchangeRatesClientInterface
{
    public function getFiatCurrencies(): Response
    {
        return response_from_client(data: $this->mockData('currencies.json'));
    }

    private function mockData(string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: domain_path('OpenExchangeRates', "Resources/mocks/{$path}")
        );

        return json_decode(json: $json, associative: true);
    }
}
