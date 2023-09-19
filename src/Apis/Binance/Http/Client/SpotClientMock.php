<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;
use App\Traits\MocksData;

class SpotClientMock implements SpotClientInterface
{
    use MocksData;

    public function account(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'spot/account.json'));

        return new BinanceResponse($response);
    }
}
