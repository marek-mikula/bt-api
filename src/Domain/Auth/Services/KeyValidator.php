<?php

namespace Domain\Auth\Services;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceClient;
use Illuminate\Contracts\Cache\Repository;

class KeyValidator
{
    public function __construct(
        private readonly Repository $cache,
        private readonly BinanceClient $binanceClient,
    ) {
    }

    public function validate(string $publicKey, string $secretKey): bool
    {
        $cacheKey = $this->getKey($publicKey, $secretKey);

        return (bool) $this->cache->remember($cacheKey, now()->addHour(), function () use ($publicKey, $secretKey): bool {
            return $this->isValid($publicKey, $secretKey);
        });
    }

    private function isValid(string $publicKey, string $secretKey): bool
    {
        $keyPair = new KeyPairData($publicKey, $secretKey);

        try {
            $response = $this->binanceClient->wallet->accountStatus($keyPair);
        } catch (BinanceRequestException) {
            return false;
        }

        return $response->ok();
    }

    private function getKey(string $publicKey, string $secretKey): string
    {
        return md5("{$publicKey}.{$secretKey}");
    }
}
