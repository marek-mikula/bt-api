<?php

namespace Domain\Binance\Http\Client;

use App\Traits\MocksData;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\SpotClientInterface;

class SpotClientMock implements SpotClientInterface
{
    use MocksData;

    public function account(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'spot/account.json'));

        return new BinanceResponse($response);
    }
}
