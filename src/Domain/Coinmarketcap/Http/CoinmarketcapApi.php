<?php

namespace Domain\Coinmarketcap\Http;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Domain\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CoinmarketcapApi
{
    public function __construct(
        private readonly CoinmarketcapClientInterface $client,
    ) {
    }

    /**
     * Returns list of top cryptocurrencies
     * by market cap
     *
     * @throws InvalidArgumentException
     * @throws CoinmarketcapRequestException
     */
    public function latestByCap(int $page = 1, int $perPage = 100): Response
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Parameter $page must be greater or equal to 1.');
        }

        if (($perPage % 5) !== 0) {
            throw new InvalidArgumentException('Parameter $perPage must be a multiple of 5');
        }

        if ($perPage > 200) {
            throw new InvalidArgumentException('Parameter $perPage must be less or equal to 200');
        }

        return $this->client->latestByCap($page, $perPage);
    }

    /**
     * Returns metadata of a cryptocurrencies based
     * on IDs from Coinmarketcap
     *
     * @throws InvalidArgumentException
     * @throws CoinmarketcapRequestException
     */
    public function coinMetadata(int|array $id): Response
    {
        $id = collect(Arr::wrap($id))->map('intval');

        if ($id->isEmpty()) {
            throw new InvalidArgumentException('Cannot get metadata for no tokens.');
        }

        // check number of IDs, so we don't waste our credits
        // 100 tokens = 1 credit
        if ($id->count() > 200) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 200.');
        }

        return $this->client->coinMetadata($id);
    }

    /**
     * Returns metadata of a cryptocurrencies based
     * on tickers/symbols (BTC, ETH...)
     *
     * @throws InvalidArgumentException
     * @throws CoinmarketcapRequestException
     */
    public function coinMetadataByTicker(string|array $ticker): Response
    {
        $ticker = collect(Arr::wrap($ticker))->map([Str::class, 'upper']);

        if ($ticker->isEmpty()) {
            throw new InvalidArgumentException('Cannot get metadata for no tokens.');
        }

        // check number of tickers, so we don't waste our credits
        // 100 tokens = 1 credit
        if ($ticker->count() > 200) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 200.');
        }

        return $this->client->coinMetadataByTicker($ticker);
    }

    /**
     * Returns information about the latest global
     * metrics
     *
     * @throws CoinmarketcapRequestException
     */
    public function latestGlobalMetrics(): Response
    {
        return $this->client->latestGlobalMetrics();
    }

    /**
     * Returns information about the key, which is used
     * to authenticate the requests
     *
     * @throws CoinmarketcapRequestException
     */
    public function keyInfo(): Response
    {
        return $this->client->keyInfo();
    }
}
