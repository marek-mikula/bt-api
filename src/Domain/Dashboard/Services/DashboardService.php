<?php

namespace Domain\Dashboard\Services;

use Apis\Coinmarketcap\Http\CoinmarketcapApi;
use App\Models\Currency;
use App\Repositories\Currency\CurrencyRepositoryInterface;
use Domain\Dashboard\Data\DashboardMarketMetrics;
use Domain\Dashboard\Data\DashboardToken;
use Exception;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
        private readonly CurrencyRepositoryInterface $currencyRepository,
        private readonly CoinmarketcapApi $coinmarketcapApi,
    ) {
    }

    /**
     * Returns basic info about the biggest cryptocurrencies by market
     * cap with icon URL
     *
     * @return Collection<DashboardToken>
     */
    public function getCryptocurrenciesByMarketCap(int $num = 10): Collection
    {
        // get the biggest tokens by market cap
        $currencies = $this->currencyRepository->topCryptocurrencies(count: $num);

        // get quotes for currencies
        $quotes = $this->coinmarketcapApi->quotes(id: $currencies->pluck('cmc_id')->all())
            ->collect('data');

        return $currencies->map(static function (Currency $currency) use ($quotes): DashboardToken {
            /** @var array|null $quote */
            $quote = $quotes->get($currency->cmc_id);

            if (! $quote) {
                throw new Exception("Missing quote for CMC ID {$currency->cmc_id}.");
            }

            $quoteCurrency = (string) collect($quote['quote'])->keys()->first();

            return DashboardToken::from([
                'currency' => $currency,
                'quoteCurrency' => $quoteCurrency,
                'quotePrice' => floatval($quote['quote'][$quoteCurrency]['price']),
            ]);
        });
    }

    /**
     * Returns latest market global metrics
     */
    public function getLatestMarketMetrics(): DashboardMarketMetrics
    {
        $data = $this->coinmarketcapApi->latestGlobalMetrics()
            ->json('data');

        $quoteCurrency = (string) collect($data['quote'])->keys()->first();

        return DashboardMarketMetrics::from([
            'ethDominance' => floatval($data['eth_dominance']) / 100,
            'ethDominanceYesterday' => floatval($data['eth_dominance_yesterday']) / 100,
            'ethDominancePercentageChange' => floatval($data['eth_dominance_24h_percentage_change']) / 100,
            'btcDominance' => floatval($data['btc_dominance']) / 100,
            'btcDominanceYesterday' => floatval($data['btc_dominance_yesterday']) / 100,
            'btcDominancePercentageChange' => floatval($data['btc_dominance_24h_percentage_change']) / 100,
            'totalMarketCap' => floatval($data['quote'][$quoteCurrency]['total_market_cap']),
            'totalMarketCapYesterday' => floatval($data['quote'][$quoteCurrency]['total_market_cap_yesterday']),
            'totalMarketCapPercentageChange' => floatval($data['quote'][$quoteCurrency]['total_market_cap_yesterday_percentage_change']) / 100,
            'totalMarketCapCurrency' => $quoteCurrency,
        ]);
    }
}
