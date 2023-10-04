<?php

namespace Apis\Cryptopanic\Http\Client;

use Apis\Cryptopanic\Http\Client\Concerns\CryptopanicClientInterface;
use App\Traits\MocksData;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CryptopanicClientMock implements CryptopanicClientInterface
{
    use MocksData;

    public function latestNews(Collection $currencies = null): Response
    {
        if ($currencies === null) {
            return response_from_client(data: $this->mockData('Cryptopanic', 'latest-news.json'));
        }

        // collect data for each given currency

        // first load empty json
        $data = $this->mockData('Cryptopanic', 'empty.json');

        foreach ($currencies as $currency) {
            $currency = Str::lower($currency);

            // there is no mock file for this specific currency
            // => skip
            if (! file_exists(api_path('Cryptopanic', "Resources/mocks/latest-news-{$currency}.json"))) {
                continue;
            }

            $currencyData = $this->mockData('Cryptopanic', "latest-news-{$currency}.json");

            // merge currency data to the mail data variable
            $data['results'] = array_merge(
                array_values($data['results']),
                array_values($currencyData['results'])
            );
        }

        return response_from_client(data: $data);
    }
}
