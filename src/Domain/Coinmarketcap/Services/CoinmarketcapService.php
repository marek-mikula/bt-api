<?php

namespace Domain\Coinmarketcap\Services;

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
    public function getCryptocurrenciesByCap(int $num = 10): Collection
    {
        // get the biggest tokens by market cap
        $tokens = $this->client->latestByCap()
            ->collect('data')
            ->take($num);

        // get metadata for each token
        $metadata = $this->client->coinMetadata($tokens->pluck('id')->toArray())
            ->collect('data');

        // map objects to data objects
        return $tokens->map(static function(array $token) use ($metadata): Token {
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
}
