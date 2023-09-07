<?php

namespace Domain\OpenExchangeRates\Http\Client;

use Domain\OpenExchangeRates\Exceptions\OpenExchangeRatesRequestException;
use Domain\OpenExchangeRates\Http\Client\Concerns\OpenExchangeRatesClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class OpenExchangeRatesClient implements OpenExchangeRatesClientInterface
{
    public function __construct(
        private readonly Repository $config,
    ) {
    }

    public function getFiatCurrencies(): Response
    {
        $response = $this->request()->get('/currencies.json');

        if (! $response->successful()) {
            throw new OpenExchangeRatesRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) $this->config->get('open-exchange-rates.url'))
            ->contentType('application/json');
    }
}
