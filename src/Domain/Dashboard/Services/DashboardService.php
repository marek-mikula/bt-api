<?php

namespace Domain\Dashboard\Services;

use Domain\Coinmarketcap\Http\CoinmarketcapApi;
use Domain\Dashboard\Data\DashboardMarketMetrics;
use Domain\Dashboard\Data\DashboardToken;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(
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
        $tokens = $this->coinmarketcapApi->latestByCap(perPage: $num)
            ->collect('data');

        // get metadata for each token
        $metadata = $this->coinmarketcapApi->coinMetadata($tokens->pluck('id')->toArray())
            ->collect('data');

        // map objects to data objects
        return $tokens->map(static function (array $token) use ($metadata): DashboardToken {
            $quoteCurrency = (string) collect($token['quote'])->keys()->first();

            $tokenMetadata = $metadata->get((int) $token['id'], '');

            return DashboardToken::from([
                'id' => (int) $token['id'],
                'name' => (string) $token['name'],
                'symbol' => (string) $token['symbol'],
                'slug' => (string) $token['slug'],
                'quoteCurrency' => $quoteCurrency,
                'quotePrice' => floatval($token['quote'][$quoteCurrency]['price']),
                'iconUrl' => (string) ($tokenMetadata['logo'] ?? ''),
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
            'ethDominance' => floatval($data['eth_dominance']),
            'ethDominanceYesterday' => floatval($data['eth_dominance_yesterday']),
            'ethDominancePercentageChange' => floatval($data['eth_dominance_24h_percentage_change']),
            'btcDominance' => floatval($data['btc_dominance']),
            'btcDominanceYesterday' => floatval($data['btc_dominance_yesterday']),
            'btcDominancePercentageChange' => floatval($data['btc_dominance_24h_percentage_change']),
            'totalMarketCap' => floatval($data['quote'][$quoteCurrency]['total_market_cap']),
            'totalMarketCapYesterday' => floatval($data['quote'][$quoteCurrency]['total_market_cap_yesterday']),
            'totalMarketCapPercentageChange' => floatval($data['quote'][$quoteCurrency]['total_market_cap_yesterday_percentage_change']),
            'totalMarketCapCurrency' => $quoteCurrency,
        ]);
    }
}
