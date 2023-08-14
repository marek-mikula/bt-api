<?php

namespace Domain\CoinRanking\Http;

use Domain\CoinRanking\Exceptions\CoinrankingRequestException;
use Domain\CoinRanking\Http\Concerns\CoinrankingClientInterface;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CoinrankingClient implements CoinrankingClientInterface
{
    public function __construct(
        private readonly Repository $config,
    ) {
    }

    public function search(string $query): Response
    {
        $response = $this->request()
            ->get('/search-suggestions', [
                'query' => $query,
                'referenceCurrencyUuid' => 'yhjMzLPhuIDl', // USD
            ]);

        if (! $response->successful()) {
            throw new CoinrankingRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) $this->config->get('coinranking.url'))
            ->withHeaders([
                'x-access-token' => (string) $this->config->get('coinranking.key'),
            ])
            ->contentType('application/json');
    }
}
