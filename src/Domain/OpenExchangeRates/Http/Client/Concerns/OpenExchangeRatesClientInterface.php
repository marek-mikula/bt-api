<?php

namespace Domain\OpenExchangeRates\Http\Client\Concerns;

use Domain\OpenExchangeRates\Exceptions\OpenExchangeRatesRequestException;
use Illuminate\Http\Client\Response;

interface OpenExchangeRatesClientInterface
{
    /**
     * @throws OpenExchangeRatesRequestException
     */
    public function getFiatCurrencies(): Response;
}
