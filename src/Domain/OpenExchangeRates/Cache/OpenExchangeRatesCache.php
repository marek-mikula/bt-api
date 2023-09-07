<?php

namespace Domain\OpenExchangeRates\Cache;

use Domain\OpenExchangeRates\Http\OpenExchangeRatesApi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class OpenExchangeRatesCache
{
    /**
     * @return Collection<string>
     */
    public function getListOfFiatCurrencies(): Collection
    {
        return Cache::tags([
            'open-exchange-rates',
            'open-exchange-rates-fiat',
        ])->remember('open-exchange-rates:fiat', now()->endOfDay(), function (): Collection {
            /** @var OpenExchangeRatesApi $api */
            $api = app(OpenExchangeRatesApi::class);

            return $api->getFiatCurrencies()->collect()->keys();
        });
    }
}
