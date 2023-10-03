<?php

namespace Domain\Cryptocurrency\Services;

use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use App\Models\Currency;
use App\Models\User;
use App\Repositories\Cryptocurrency\CurrencyRepositoryInterface;
use App\Repositories\WhaleAlert\WhaleAlertRepositoryInterface;
use Domain\Cryptocurrency\Data\CryptocurrencyListData;
use Domain\Cryptocurrency\Data\CryptocurrencyShowData;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CryptocurrencyService
{
    public function __construct(
        private readonly WhaleAlertRepositoryInterface $whaleAlertRepository,
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

        $ids = $cryptocurrencies->pluck('cmc_id');

        // retrieve current quotes for given IDs

        $quotes = $this->coinmarketcapApi->quotes($ids->all())
            ->collect('data');

        return $cryptocurrencies
            ->through(static function (Currency $currency) use ($quotes): CryptocurrencyListData {
                $quote = $quotes->get($currency->cmc_id);

                if (! $quote) {
                    throw new Exception("Missing quote for CMC ID {$currency->cmc_id}.");
                }

                $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

                return CryptocurrencyListData::from([
                    'currency' => $currency,
                    'quote' => [
                        'currency' => $quoteCurrency,
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
                    ],
                ]);
            });
    }

    public function getDataForShow(User $user, Currency $cryptocurrency): CryptocurrencyShowData
    {
        // retrieve current quotes for given currency

        $quote = $this->coinmarketcapApi->quotes($cryptocurrency->cmc_id)
            ->collect('data')
            ->first();

        if (! $quote) {
            throw new Exception("Missing quote for CMC ID {$cryptocurrency->cmc_id}.");
        }

        $supportsWhaleAlerts = in_array(Str::lower($cryptocurrency->symbol), config('whale-alert.supported_currencies', []));

        $whaleAlerts = $supportsWhaleAlerts
            ? $this->whaleAlertRepository->latest($user, count: 5, currency: $cryptocurrency)
            : null;

        $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

        return CryptocurrencyShowData::from([
            'currency' => $cryptocurrency,
            'quote' => [
                'currency' => $quoteCurrency,
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
            ],
            'whaleAlerts' => $whaleAlerts,
        ]);
    }
}
