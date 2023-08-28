<?php

namespace Domain\Coinranking\Http;

use Domain\Coinranking\Exceptions\CoinrankingRequestException;
use Domain\Coinranking\Http\Client\Concerns\CoinrankingClientInterface;
use Illuminate\Http\Client\Response;

class CoinrankingApi
{
    public function __construct(
        private readonly CoinrankingClientInterface $client,
    ) {
    }

    /**
     * Searches cryptocurrencies by given query
     *
     * @throws CoinrankingRequestException
     */
    public function search(string $query): Response
    {
        return $this->client->search($query);
    }
}
