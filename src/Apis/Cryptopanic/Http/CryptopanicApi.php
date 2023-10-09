<?php

namespace Apis\Cryptopanic\Http;

use Apis\Cryptopanic\Exceptions\CryptopanicRequestException;
use Apis\Cryptopanic\Http\Client\Concerns\CryptopanicClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class CryptopanicApi
{
    public function __construct(
        private readonly CryptopanicClientInterface $client,
    ) {
    }

    /**
     * @throws CryptopanicRequestException
     */
    public function latestNews(string|array $currency = null): Response
    {
        if (empty($currency)) {
            $currency = null;
        } else {
            $currency = collect(Arr::wrap($currency))->map([Str::class, 'upper']);
        }

        if ($currency?->count() > 50) {
            throw new InvalidArgumentException('Number of currencies cannot be higher than 50.');
        }

        return $this->client->latestNews($currency);
    }
}
