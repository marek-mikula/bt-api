<?php

namespace Domain\Coinmarketcap\Http;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class CoinmarketcapClient implements CoinmarketcapClientInterface
{
    public function __construct(
        private readonly Repository $config
    ) {
    }

    public function latestByCap(): Response
    {
        $response = $this->request()
            ->get('/v1/cryptocurrency/listings/latest', [
                'start' => 1,
                'limit' => 100, // 100 rows = 1 credit
                'sort' => 'market_cap',
                'sort_dir' => 'desc',
                'cryptocurrency_type' => 'all',
                'tag' => 'all',
                'convert' => 'USD',
            ]);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function coinMetadata(int|array $id): Response
    {
        $id = collect(Arr::wrap($id));

        if ($id->isEmpty()) {
            throw new InvalidArgumentException('Cannot get metadata for no tokens.');
        }

        // check number of tokens, so we don't waste our credits
        if ($id->count() > 100) {
            throw new InvalidArgumentException('Cannot get metadata for that number of tokens. Number must be <= 100.');
        }

        $response = $this->request()
            ->get('/v2/cryptocurrency/info', [
                'id' => $id->implode(','),
            ]);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function latestGlobalMetrics(): Response
    {
        $response = $this->request()
            ->get('/v1/global-metrics/quotes/latest', [
                'convert' => 'USD',
            ]);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function keyInfo(): Response
    {
        $response = $this->request()
            ->get('/v1/key/info');

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) $this->config->get('coinmarketcap.url'))
            ->withHeaders([
                'X-CMC_PRO_API_KEY' => (string) $this->config->get('coinmarketcap.key'),
                'Accept-Encoding' => 'deflate, gzip',
            ])
            ->acceptJson();
    }
}