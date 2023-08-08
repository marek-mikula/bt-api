<?php

namespace Domain\CoinMarketCap\Http;

use Domain\CoinMarketCap\Exceptions\CoinMarketCapRequestException;
use Domain\CoinMarketCap\Http\Concerns\CoinMarketCapClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CoinMarketCapClient implements CoinMarketCapClientInterface
{
    public function __construct(
        private readonly Repository $config
    ) {
    }

    public function latestByCap(): Response
    {
        $response = $this->request()
            ->get('/cryptocurrency/listings/latest', [
                'start' => 1,
                'limit' => 10,
                'sort' => 'market_cap',
                'sort_dir' => 'desc',
                'cryptocurrency_type' => 'all',
                'tag' => 'all',
            ]);

        if (! $response->successful()) {
            throw new CoinMarketCapRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        $version = $this->config->get('services.coinmarketcap.version');
        $url = $this->config->get('services.coinmarketcap.url');

        if (Str::endsWith($url, '/')) {
            $url = Str::beforeLast($url, '/');
        }

        $url = "{$url}/{$version}";

        return Http::baseUrl($url)
            ->withHeaders([
                'X-CMC_PRO_API_KEY' => $this->config->get('services.coinmarketcap.key'),
                'Accept-Encoding' => 'deflate, gzip'
            ])
            ->acceptJson();
    }
}
