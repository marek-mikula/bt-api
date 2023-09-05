<?php

namespace Domain\Binance\Http\Client;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Http\Client\Concerns\WalletClientInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

class WalletClientMock implements WalletClientInterface
{
    public function systemStatus(): Response
    {
        return response_from_client(data: $this->mockData('system-status.json'));
    }

    public function accountStatus(KeyPairData $keyPair): Response
    {
        return response_from_client(data: $this->mockData('account-status.json'));
    }

    public function accountSnapshot(KeyPairData $keyPair): Response
    {
        return response_from_client(data: $this->mockData('account-snapshot.json'));
    }

    public function assets(KeyPairData $keyPair): Response
    {
        return response_from_client(data: $this->mockData('assets.json'));
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
