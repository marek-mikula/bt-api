<?php

namespace Domain\Coinmarketcap\Http;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Domain\Coinmarketcap\Http\Concerns\CoinmarketcapClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

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

    public function coinMetadata(int $id): Response
    {
        $response = $this->request()
            ->get('/v2/cryptocurrency/info', [
                'id' => $id,
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
        $baseUrl = (string) $this->config->get('coinmarketcap.url');

        return Http::baseUrl($baseUrl)
            ->withHeaders([
                'X-CMC_PRO_API_KEY' => (string) $this->config->get('coinmarketcap.key'),
                'Accept-Encoding' => 'deflate, gzip'
            ])
            ->acceptJson();
    }
}
