<?php

namespace Apis\Cryptopanic\Http\Client;

use Apis\Cryptopanic\Http\Client\Concerns\CryptopanicClientInterface;
use App\Traits\MocksData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

class CryptopanicClientMock implements CryptopanicClientInterface
{
    use MocksData;

    public function latestNews(Collection $currencies = null): Response
    {
        return response_from_client(data: $this->mockData('Cryptopanic', 'latest-news.json'));
    }
}
