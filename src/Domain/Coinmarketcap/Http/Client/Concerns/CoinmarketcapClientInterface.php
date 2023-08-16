<?php

namespace Domain\Coinmarketcap\Http\Client\Concerns;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

interface CoinmarketcapClientInterface
{
    /**
     * @throws CoinmarketcapRequestException
     */
    public function latestByCap(int $page = 1, int $perPage = 100): Response;

    /**
     * @param  Collection<int>  $ids
     *
     * @throws CoinmarketcapRequestException
     */
    public function coinMetadata(Collection $ids): Response;

    /**
     * @throws CoinmarketcapRequestException
     */
    public function latestGlobalMetrics(): Response;

    /**
     *
     *
     * @throws CoinmarketcapRequestException
     */
    public function keyInfo(): Response;
}
