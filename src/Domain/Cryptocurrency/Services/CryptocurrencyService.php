<?php

namespace Domain\Cryptocurrency\Services;

use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use Apis\Cryptopanic\Http\CryptopanicApi;
use App\Models\Currency;
use App\Models\User;
use App\Repositories\Currency\CurrencyRepositoryInterface;
use App\Repositories\WhaleAlert\WhaleAlertRepositoryInterface;
use Domain\Cryptocurrency\Data\CryptocurrencyListData;
use Domain\Cryptocurrency\Data\CryptocurrencyShowData;
use Domain\Cryptocurrency\Data\NewsData;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CryptocurrencyService
{
    public function __construct(
        private readonly WhaleAlertRepositoryInterface $whaleAlertRepository,
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly CoinmarketcapApi $coinmarketcapApi,
        private readonly CryptopanicApi $cryptopanicApi,
    ) {
    }

    public function getDataForIndex(int $page, int $perPage = 50): LengthAwarePaginator
    {
        if ($page < 1) {
            $page = 1;
        }

        $cryptocurrencies = $this->currencyRepository->cryptocurrenciesIndex(
            page: $page,
            perPage: $perPage
        );

        // retrieve current quotes for given IDs

        $quotes = $this->coinmarketcapApi->quotes(id: $cryptocurrencies->pluck('cmc_id')->all())
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

    public function getDataForShow(
        User $user,
        Currency $cryptocurrency,
        int $whaleAlertsCount = 5,
        int $newsCount = 5,
    ): CryptocurrencyShowData {
        // load needed relationships
        $cryptocurrency->loadMissing('quoteCurrencies');

        // retrieve current quotes for given currency

        $quote = $this->coinmarketcapApi->quotes($cryptocurrency->cmc_id)
            ->collect('data')
            ->first();

        if (! $quote) {
            throw new Exception("Missing quote for CMC ID {$cryptocurrency->cmc_id}.");
        }

        $supportsWhaleAlerts = in_array(Str::lower($cryptocurrency->symbol), config('whale-alert.supported_currencies', []));

        $whaleAlerts = $supportsWhaleAlerts
            ? $this->whaleAlertRepository->latest($user, count: $whaleAlertsCount, currency: $cryptocurrency)
            : null;

        $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

        $news = $this->cryptopanicApi->latestNews($cryptocurrency->symbol)
            ->collect('results')
            ->take($newsCount)
            ->map(static fn (array $item): NewsData => NewsData::from([
                'id' => (int) $item['id'],
                'title' => (string) $item['title'],
                'url' => (string) $item['url'],
                'createdAt' => (string) $item['created_at'],
                'publishedAt' => (string) $item['published_at'],
                'sourceName' => (string) $item['source']['title'],
                'sourceUrl' => "https://www.{$item['source']['domain']}",
            ]));

        return CryptocurrencyShowData::from([
            'currency' => $cryptocurrency,
            'news' => $news,
            'whaleAlerts' => $whaleAlerts,
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
    }
}
