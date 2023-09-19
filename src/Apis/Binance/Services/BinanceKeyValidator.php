<?php

namespace Apis\Binance\Services;

use Apis\Binance\Data\KeyPairData;
use Apis\Binance\Exceptions\BinanceRequestException;
use Apis\Binance\Http\BinanceApi;
use Illuminate\Support\Facades\Cache;

class BinanceKeyValidator
{
    public function __construct(
        private readonly BinanceApi $binanceApi,
    ) {
    }

    public function validate(string $publicKey, string $secretKey): bool
    {
        $cacheKey = $this->getKey($publicKey, $secretKey);

        return (bool) Cache::tags(['binance', 'binance-key-validation'])->remember($cacheKey, now()->addHour(), function () use ($publicKey, $secretKey): bool {
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
