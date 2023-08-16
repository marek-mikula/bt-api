<?php

namespace Domain\Coinranking\Http\Concerns;

use Domain\Coinranking\Exceptions\CoinrankingRequestException;
use Illuminate\Http\Client\Response;

interface CoinrankingClientInterface
{
    /**
     * Searches cryptocurrencies by given query
     *
     * @throws CoinrankingRequestException
     */
    public function search(string $query): Response;
}
