<?php

namespace Domain\Currency\Services;

use Apis\Binance\Http\BinanceApi;
use App\Models\CurrencyPair;

class PairService
{
    public function __construct(private readonly BinanceApi $binanceApi)
    {
    }

    public function getPairPrice(CurrencyPair $pair): float
    {
        $response = $this->binanceApi->marketData->symbolPrice($pair->symbol);

        $price = floatval($response->json('price'));

        // change price a little if we are using mocked
        // data, so we can simulate price changes over time
        if (config('binance.mock')) {
            $price = $price * (rand(90, 110) / 100);
        }

        return floatval($price);
    }
}
