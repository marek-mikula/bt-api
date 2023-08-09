<?php

namespace Domain\CoinMarketCap\Http\Concerns;

use Domain\CoinMarketCap\Exceptions\CoinMarketCapRequestException;
use Illuminate\Http\Client\Response;

interface CoinMarketCapClientInterface
{
    /**
     * Returns list of top 100 cryptocurrencies
     * by market cap
     *
     * @throws CoinMarketCapRequestException
     */
    public function latestByCap(): Response;

    /**
     * Returns metadata of a cryptocurrency based
     * on ID from CoinMarketCap
     *
     * @throws CoinMarketCapRequestException
     */
    public function coinMetadata(int $id): Response;

    /**
     * Returns information about the latest global
     * metrics
     *
     * @throws CoinMarketCapRequestException
     */
    public function latestGlobalMetrics(): Response;

    /**
     * Returns information about the key, which is used
     * to authenticate the requests
     *
     * @throws CoinMarketCapRequestException
     */
    public function keyInfo(): Response;
}
