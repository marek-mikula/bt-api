<?php

namespace Domain\CoinRanking\Http\Concerns;

use Domain\CoinRanking\Exceptions\CoinrankingRequestException;
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
