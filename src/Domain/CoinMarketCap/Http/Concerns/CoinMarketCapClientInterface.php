<?php

namespace Domain\CoinMarketCap\Http\Concerns;

use Domain\CoinMarketCap\Exceptions\CoinMarketCapRequestException;
use Illuminate\Http\Client\Response;

interface CoinMarketCapClientInterface
{
    /**
     * @throws CoinMarketCapRequestException
     */
    public function latestByCap(): Response;

    /**
     * @param int $id CoinMarketCap ID
     * @throws CoinMarketCapRequestException
     */
    public function coinMetadata(int $id): Response;
}
