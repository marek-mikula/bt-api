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
     * @return Collection<Token>
     */
    public function getCryptocurrenciesByCap(int $num = 10): Collection
    {
        return $this->client->latestByCap()
            ->collect('data')
            ->take($num)
            ->map(static function(array $token): Token {
                $quoteCurrency = (string) collect($token['quote'])->keys()->first();

                return Token::from([
                    'id' => (int) $token['id'],
                    'name' => (string) $token['name'],
                    'symbol' => (string) $token['symbol'],
                    'slug' => (string) $token['slug'],
                    'quoteCurrency' => $quoteCurrency,
                    'quotePrice' => floatval($token['quote'][$quoteCurrency]['price']),
                ]);
            });
    }
}
