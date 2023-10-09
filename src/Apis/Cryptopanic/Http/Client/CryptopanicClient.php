<?php

namespace Apis\Cryptopanic\Http\Client;

use Apis\Cryptopanic\Exceptions\CryptopanicRequestException;
use Apis\Cryptopanic\Http\Client\Concerns\CryptopanicClientInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CryptopanicClient implements CryptopanicClientInterface
{
    public function latestNews(Collection $currencies = null): Response
    {
        $token = (string) config('cryptopanic.key');

        $params = [
            'auth_token' => $token,
            'kind' => 'news', // return only news
        ];

        // append currency codes if any
        if ($currencies !== null) {
            $params['currencies'] = $currencies->implode(',');
        }

        $response = $this->request()
            ->get('/api/v1/posts/', $params);

        if (! $response->successful()) {
            throw new CryptopanicRequestException($response);
        }

        return $response;
    }

    private function request(): PendingRequest
    {
        return Http::baseUrl((string) config('cryptopanic.url'))
            ->acceptJson();
    }
}
