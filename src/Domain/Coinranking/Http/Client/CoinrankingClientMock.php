<?php

namespace Domain\Coinranking\Http\Client;

use App\Traits\MocksData;
use Domain\Coinranking\Http\Client\Concerns\CoinrankingClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class CoinrankingClientMock implements CoinrankingClientInterface
{
    use MocksData;

    public function search(string $query): Response
    {
        $data = $this->mockData('Coinranking', 'search.json');

        $query = Str::lower($query);

        // get all keys which at least partially match
        // with given query

        $keys = collect($data)
            ->keys()
            ->filter(function (string $key) use ($query): bool {
                return $key !== 'empty' && Str::contains($key, $query);
            })
            ->all();

        // start with empty data response

        $responseData = $data['empty'];

        // merge all the matched keys to the empty array

        foreach ($keys as $key) {
            $responseData['data']['coins'] = array_merge($responseData['data']['coins'], $data[$key]['data']['coins']);
            $responseData['data']['exchanges'] = array_merge($responseData['data']['exchanges'], $data[$key]['data']['exchanges']);
            $responseData['data']['markets'] = array_merge($responseData['data']['markets'], $data[$key]['data']['markets']);
        }

        // filter out possible duplicates

        $responseData['data']['coins'] = collect($responseData['data']['coins'])
            ->unique('uuid')
            ->all();

        $responseData['data']['exchanges'] = collect($responseData['data']['exchanges'])
            ->unique('uuid')
            ->all();

        $responseData['data']['markets'] = collect($responseData['data']['markets'])
            ->unique('uuid')
            ->all();

        return response_from_client(data: $responseData);
    }
}
