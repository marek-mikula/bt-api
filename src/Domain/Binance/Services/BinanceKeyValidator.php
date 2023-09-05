<?php

namespace Domain\Binance\Services;

use Domain\Binance\Data\KeyPairData;
use Domain\Binance\Exceptions\BinanceRequestException;
use Domain\Binance\Http\BinanceApi;
use Illuminate\Contracts\Cache\Repository;

class BinanceKeyValidator
{
    public function __construct(
        private readonly Repository $cache,
        private readonly BinanceApi $binanceApi,
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
        $keyPair = KeyPairData::fromRaw($publicKey, $secretKey);

        try {
            return $this->binanceApi->wallet->accountStatus($keyPair)->ok();
        } catch (BinanceRequestException) {
            return false;
        }
    }

    private function getKey(string $publicKey, string $secretKey): string
    {
        return md5("{$publicKey}-{$secretKey}");
    }
}