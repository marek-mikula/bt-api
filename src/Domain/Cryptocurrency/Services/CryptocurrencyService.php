<?php

namespace Domain\Cryptocurrency\Services;

use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use App\Models\Currency;
use App\Repositories\Cryptocurrency\CurrencyRepositoryInterface;
use Domain\Cryptocurrency\Data\Cryptocurrency;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

class CryptocurrencyService
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly CoinmarketcapApi $coinmarketcapApi,
    ) {
    }

    public function getDataForIndex(int $page, int $perPage = 50): LengthAwarePaginator
    {
        if ($page < 1) {
            $page = 1;
        }

        $cryptocurrencies = $this->currencyRepository->cryptocurrencies(
            page: $page,
            perPage: $perPage
        );

        $ids = $cryptocurrencies->pluck('coinmarketcap_id');

        // retrieve current quotes for given IDs

        $quotes = $this->coinmarketcapApi->quotes($ids->all())
            ->collect('data');

        $collection = $cryptocurrencies
            ->getCollection()
            ->map(static function (Currency $currency) use ($quotes): Cryptocurrency {
                $quote = $quotes->get($currency->coinmarketcap_id);

                if (! $quote) {
                    throw new Exception("Missing quote for CMC ID {$currency->coinmarketcap_id}.");
                }

                $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

                return Cryptocurrency::from([
                    'currency' => $currency,
                    'quoteCurrency' => $quoteCurrency,
                    'infiniteSupply' => (bool) $quote['infinite_supply'],
                    'totalSupply' => floatval($quote['total_supply']),
                    'circulatingSupply' => floatval($quote['circulating_supply']),
                    'maxSupply' => (int) $quote['max_supply'],
                    'price' => floatval($quote['quote'][$quoteCurrency]['price']),
                    'priceChange1h' => floatval($quote['quote'][$quoteCurrency]['percent_change_1h']) / 100,
                    'priceChange24h' => floatval($quote['quote'][$quoteCurrency]['percent_change_24h']) / 100,
                    'priceChange7d' => floatval($quote['quote'][$quoteCurrency]['percent_change_7d']) / 100,
                    'priceChange30d' => floatval($quote['quote'][$quoteCurrency]['percent_change_30d']) / 100,
                    'priceChange60d' => floatval($quote['quote'][$quoteCurrency]['percent_change_60d']) / 100,
                    'priceChange90d' => floatval($quote['quote'][$quoteCurrency]['percent_change_90d']) / 100,
                    'marketCap' => floatval($quote['quote'][$quoteCurrency]['market_cap']),
                    'volume24h' => floatval($quote['quote'][$quoteCurrency]['volume_24h']),
                    'volumeChange24h' => floatval($quote['quote'][$quoteCurrency]['volume_change_24h']) / 100,
                ]);
            });

        $cryptocurrencies->setCollection($collection);

        return $cryptocurrencies;
    }
}
