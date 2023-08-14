<?php

namespace Domain\Coinmarketcap\Services;

use Domain\Coinmarketcap\Data\MarketMetrics;
use Domain\Coinmarketcap\Data\Token;
use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Support\Collection;

class CoinmarketcapService
{
    public function __construct(
        private readonly CoinmarketcapClientInterface $client,
    ) {
    }

    /**
     * Returns basic info about the biggest cryptocurrencies by market
     * cap with icon URL
     *
     * @return Collection<Token>
     */
    public function getCryptocurrenciesByMarketCap(int $num = 10): Collection
    {
        // get the biggest tokens by market cap
        $tokens = $this->client->latestByCap()
            ->collect('data')
            ->take($num);

        // get metadata for each token
        $metadata = $this->client->coinMetadata($tokens->pluck('id')->toArray())
            ->collect('data');

        // map objects to data objects
        return $tokens->map(static function (array $token) use ($metadata): Token {
            $quoteCurrency = (string) collect($token['quote'])->keys()->first();

            $tokenMetadata = $metadata->get((int) $token['id'], '');

            return Token::from([
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
    public function getLatestMarketMetrics(): MarketMetrics
    {
        $data = $this->client->latestGlobalMetrics()
            ->json('data');

        $quoteCurrency = (string) collect($data['quote'])->keys()->first();

        return MarketMetrics::from([
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
