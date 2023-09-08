<?php

namespace Domain\OpenExchangeRates\Http\Client;

use App\Traits\MocksData;
use Domain\OpenExchangeRates\Http\Client\Concerns\OpenExchangeRatesClientInterface;
use Illuminate\Http\Client\Response;

class OpenExchangeRatesClientMock implements OpenExchangeRatesClientInterface
{
    use MocksData;

    public function getFiatCurrencies(): Response
    {
        return response_from_client(data: $this->mockData('OpenExchangeRates', 'currencies.json'));
    }
}
