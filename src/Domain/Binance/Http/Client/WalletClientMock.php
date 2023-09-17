<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\BinanceResponse;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Illuminate\Support\Str;

class WalletClientMock implements WalletClientInterface
{
    public function systemStatus(): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('system-status.json'));

        return new BinanceResponse($response);
    }

    public function accountStatus(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('account-status.json'));

        return new BinanceResponse($response);
    }

    public function accountSnapshot(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('account-snapshot.json'));

        return new BinanceResponse($response);
    }

    public function assets(KeyPairData $keyPair): BinanceResponse
    {
        $response = response_from_client(data: $this->mockData('assets.json'));

        return new BinanceResponse($response);
    }

    private function mockData(string $path): array
    {
        $path = Str::startsWith($path, '/') ? Str::after($path, '/') : $path;

        $json = file_get_contents(
            filename: domain_path('Binance', "Resources/mocks/{$path}")
        );

        return json_decode(json: $json, associative: true);
    }
}
