<?php

namespace Domain\Coinmarketcap\Http;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Domain\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CoinmarketcapApi
{
    public function __construct(
        private readonly CoinmarketcapClientInterface $client,
    ) {
    }

    /**
     * Returns paginated list of top cryptocurrencies
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

        if ($perPage < 5) {
            throw new InvalidArgumentException('Parameter $perPage must be at least 5.');
        }

        if ($perPage > 5000) {
            throw new InvalidArgumentException('Parameter $perPage must be less or equal to 5000.');
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
        if ($id->count() > 5000) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 5000.');
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
    public function coinMetadataBySymbol(string|array $symbol): Response
    {
        $symbol = collect(Arr::wrap($symbol))->map([Str::class, 'upper']);

        if ($symbol->isEmpty()) {
            throw new InvalidArgumentException('Cannot get metadata for no tokens.');
        }

        // check number of tickers, so we don't waste our credits
        // 100 tokens = 1 credit
        if ($symbol->count() > 5000) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 5000.');
        }

        return $this->client->coinMetadataBySymbol($symbol);
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
     * Returns the latest quotes for given cryptocurrency IDs
     *
     * @throws CoinmarketcapRequestException
     */
    public function quotesLatest(int|array $id): Response
    {
        $id = collect(Arr::wrap($id))->map('intval');

        if ($id->isEmpty()) {
            throw new InvalidArgumentException('Cannot get quotes for no tokens.');
        }

        // check number of IDs, so we don't waste our credits
        // 100 tokens = 1 credit
        if ($id->count() > 100) {
            throw new InvalidArgumentException('Cannot get quotes for that number of tokens. Number must be <= 100.');
        }

        return $this->client->quotesLatest($id);
    }

    /**
     * Returns the latest quotes for given cryptocurrency tickers
     *
     * @throws CoinmarketcapRequestException
     */
    public function quotesLatestBySymbol(string|array $symbol): Response
    {
        $symbol = collect(Arr::wrap($symbol))->map([Str::class, 'upper']);

        if ($symbol->isEmpty()) {
            throw new InvalidArgumentException('Cannot get quotes for no tokens.');
        }

        // check number of tickers, so we don't waste our credits
        // 100 tokens = 1 credit
        if ($symbol->count() > 100) {
            throw new InvalidArgumentException('Cannot get quotes for that number of tokens. Number must be <= 100.');
        }

        return $this->client->quotesLatestBySymbol($symbol);
    }

    /**
     * Returns the paginated map for symbols and IDs, if a collection
     * of symbols is passed, the pagination is ignored, and we only look
     * for those symbols
     *
     * @throws CoinmarketcapRequestException
     */
    public function map(int $page = 1, int $perPage = 100, Collection $symbols = null): Response
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Parameter $page must be greater or equal to 1.');
        }

        if ($perPage < 5) {
            throw new InvalidArgumentException('Parameter $perPage must be at least 5.');
        }

        if ($perPage > 5000) {
            throw new InvalidArgumentException('Parameter $perPage must be less or equal to 5000');
        }

        return $this->client->map($page, $perPage, $symbols);
    }

    /**
     * Returns the paginated map for fiat symbols and IDs
     *
     * @throws CoinmarketcapRequestException
     */
    public function mapFiat(int $page = 1, int $perPage = 100): Response
    {
        if ($page < 1) {
            throw new InvalidArgumentException('Parameter $page must be greater or equal to 1.');
        }

        if ($perPage < 5) {
            throw new InvalidArgumentException('Parameter $perPage must be at least 5.');
        }

        if ($perPage > 5000) {
            throw new InvalidArgumentException('Parameter $perPage must be less or equal to 5000');
        }

        return $this->client->mapFiat($page, $perPage);
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
