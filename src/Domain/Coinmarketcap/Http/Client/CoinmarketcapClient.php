<?php

namespace Domain\Coinmarketcap\Http\Client;

use Domain\Coinmarketcap\Exceptions\CoinmarketcapRequestException;
use Domain\Coinmarketcap\Http\Client\Concerns\CoinmarketcapClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CoinmarketcapClient implements CoinmarketcapClientInterface
{
    public function __construct(
        private readonly Repository $config
    ) {
    }

    public function latestByCap(int $page = 1, int $perPage = 100): Response
    {
        $response = $this->request()
            ->get('/v1/cryptocurrency/listings/latest', [
                'start' => (($page - 1) * $perPage) + 1,
                'limit' => $perPage, // 200 rows = 1 credit
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

    public function coinMetadata(Collection $ids): Response
    {
        $response = $this->request()
            ->get('/v2/cryptocurrency/info', [
                'id' => $ids->implode(','),
            ]);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function coinMetadataBySymbol(Collection $symbols): Response
    {
        $response = $this->request()
            ->get('/v2/cryptocurrency/info', [
                'symbol' => $symbols->implode(','),
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

    public function quotesLatest(Collection $ids): Response
    {
        $response = $this->request()
            ->get('/v2/cryptocurrency/quotes/latest', [
                'id' => $ids->implode(','),
            ]);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function quotesLatestBySymbol(Collection $symbols): Response
    {
        $response = $this->request()
            ->get('/v2/cryptocurrency/quotes/latest', [
                'symbol' => $symbols->implode(','),
            ]);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function map(int $page = 1, int $perPage = 100, Collection $symbols = null): Response
    {
        $params = [
            'listing_status' => 'active',
            'start' => (($page - 1) * $perPage) + 1,
            'limit' => $perPage,
            'sort' => 'id',
            'aux' => implode(',', [
                //                    'platform',
                //                    'first_historical_data',
                //                    'last_historical_data',
                //                    'is_active',
                //                    'status',
            ]),
        ];

        if ($symbols && $symbols->count() > 0) {
            $params['symbol'] = $symbols->implode(',');
        }

        $response = $this->request()
            ->get('/v1/cryptocurrency/map', $params);

        if (! $response->successful()) {
            throw new CoinmarketcapRequestException($response);
        }

        return $response;
    }

    public function fiatMap(int $page = 1, int $perPage = 100): Response
    {
        $response = $this->request()
            ->get('/v1/fiat/map', [
                'start' => (($page - 1) * $perPage) + 1,
                'limit' => $perPage,
                'sort' => 'id',
                'include_metals' => 'false',
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
