<?php

namespace Apis\Binance\Http\Client;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceResponse;
use Apis\Binance\Http\Client\Concerns\SpotClientInterface;

class SpotClient extends BinanceClient implements SpotClientInterface
{
    protected bool $supportsTestnet = true;

    public function account(KeyPairData $keyPair): BinanceResponse
    {
        $params = $this->signParams($keyPair, []);

        $response = $this->authRequest($keyPair)->get('/api/v3/account', $params);

        if ($response->failed()) {
            throw new BinanceRequestException(new BinanceResponse($response));
        }

        return new BinanceResponse($response);
    }
}
