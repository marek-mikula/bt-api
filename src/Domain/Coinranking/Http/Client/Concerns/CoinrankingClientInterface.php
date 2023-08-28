<?php

namespace Domain\Coinranking\Http\Client\Concerns;

use Domain\Coinranking\Exceptions\CoinrankingRequestException;
use Illuminate\Http\Client\Response;

interface CoinrankingClientInterface
{
    /**
     * @throws CoinrankingRequestException
     */
    public function search(string $query): Response;
}
