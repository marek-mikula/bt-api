<?php

namespace Domain\Binance\Http\Client;

use App\Traits\MocksData;
use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;

class WalletClientMock implements WalletClientInterface
{
    use MocksData;

    public function systemStatus(): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'wallet/system-status.json'));

        return new BinanceResponse($response);
    }

    public function accountStatus(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'wallet/account-status.json'));

        return new BinanceResponse($response);
    }

    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'wallet/account-snapshot.json'));

        return new BinanceResponse($response);
    }

    public function assets(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'wallet/assets.json'));

        return new BinanceResponse($response);
    }

    public function allCoins(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('Binance', 'wallet/all-coins.json'));

        return new BinanceResponse($response);
    }
}
