<?php

namespace Domain\Coinmarketcap\Http\Concerns;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Illuminate\Http\Client\Response;

interface CoinmarketcapClientInterface
{
    /**
     * Returns list of top cryptocurrencies
     * by market cap
     *
     * @throws CoinmarketcapRequestException
     */
    public function latestByCap(int $page = 1, int $perPage = 100): Response;

    /**
     * Returns metadata of a cryptocurrency/ies based
     * on ID/IDs from Coinmarketcap
     *
     * @throws CoinmarketcapRequestException
     */
    public function coinMetadata(int|array $id): Response;

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
