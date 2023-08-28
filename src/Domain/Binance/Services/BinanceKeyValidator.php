<?php

namespace Domain\Binance\Services;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceClient;
use Illuminate\Contracts\Cache\Repository;

class BinanceKeyValidator
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
            return $this->binanceClient->wallet->accountStatus($keyPair)->ok();
        } catch (BinanceRequestException) {
            return false;
        }
    }

    private function getKey(string $publicKey, string $secretKey): string
    {
        return md5("{$publicKey}-{$secretKey}");
    }
}
