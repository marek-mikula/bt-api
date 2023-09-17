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
     * @param  Collection<string>  $symbols
     *
     * @throws CoinmarketcapRequestException
     */
    public function coinMetadataBySymbol(Collection $symbols): Response;

    /**
     * @throws CoinmarketcapRequestException
     */
    public function latestGlobalMetrics(): Response;

    /**
     * @param  Collection<string>|null  $symbols
     *
     * @throws CoinmarketcapRequestException
     */
    public function map(int $page = 1, int $perPage = 100, Collection $symbols = null): Response;

    /**
     * @throws CoinmarketcapRequestException
     */
    public function mapFiat(int $page = 1, int $perPage = 100): Response;

    /**
     * @param  Collection<int>  $ids
     *
     * @throws CoinmarketcapRequestException
     */
    public function quotes(Collection $ids): Response;

    /**
     * @throws CoinmarketcapRequestException
     */
    public function keyInfo(): Response;
}
