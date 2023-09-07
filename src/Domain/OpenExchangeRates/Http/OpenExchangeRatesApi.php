<?php

namespace Domain\OpenExchangeRates\Http;

use Domain\OpenExchangeRates\Exceptions\OpenExchangeRatesRequestException;
use Domain\OpenExchangeRates\Http\Client\Concerns\OpenExchangeRatesClientInterface;
use Illuminate\Http\Client\Response;

class OpenExchangeRatesApi
{
    public function __construct(
        private readonly OpenExchangeRatesClientInterface $client,
    ) {
    }

    /**
     * @throws OpenExchangeRatesRequestException
     */
    public function getFiatCurrencies(): Response
    {
        return $this->client->getFiatCurrencies();
    }
}
