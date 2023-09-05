<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Illuminate\Http\Client\Response;

class WalletClientMock implements WalletClientInterface
{
    public function systemStatus(): Response
    {
        return response_from_client();
    }

    public function accountStatus(KeyPairData $keyPair): Response
    {
        return response_from_client();
    }

    public function accountSnapshot(KeyPairData $keyPair): Response
    {
        return response_from_client();
    }

    public function assets(KeyPairData $keyPair): Response
    {
        return response_from_client();
    }
}
