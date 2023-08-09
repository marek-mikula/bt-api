<?php

namespace Domain\Coinmarketcap\Http\Concerns;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Illuminate\Http\Client\Response;

interface CoinmarketcapClientInterface
{
    /**
     * Returns list of top 100 cryptocurrencies
     * by market cap
     *
     * @throws CoinmarketcapRequestException
     */
    public function latestByCap(): Response;

    /**
     * Returns metadata of a cryptocurrency based
     * on ID from Coinmarketcap
     *
     * @throws CoinmarketcapRequestException
     */
    public function coinMetadata(int $id): Response;

    /**
     * Returns information about the latest global
     * metrics
     *
     * @throws CoinmarketcapRequestException
     */
    public function latestGlobalMetrics(): Response;

    /**
     * Returns information about the key, which is used
     * to authenticate the requests
     *
     * @throws CoinmarketcapRequestException
     */
    public function keyInfo(): Response;
}
