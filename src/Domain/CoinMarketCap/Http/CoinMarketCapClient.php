<?php

namespace Domain\CoinMarketCap\Http;

use Domain\CoinMarketCap\Exceptions\CoinMarketCapRequestException;
use Domain\CoinMarketCap\Http\Concerns\CoinMarketCapClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CoinMarketCapClient implements CoinMarketCapClientInterface
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
            throw new CoinMarketCapRequestException($response);
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
            throw new CoinMarketCapRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        $baseUrl = $this->config->get('services.coinmarketcap.url');

        return Http::baseUrl($baseUrl)
            ->withHeaders([
                'X-CMC_PRO_API_KEY' => $this->config->get('services.coinmarketcap.key'),
                'Accept-Encoding' => 'deflate, gzip'
            ])
            ->acceptJson();
    }
}
