<?php

namespace Domain\Binance\Data;

use Spatie\LaravelData\Data;

class KeyPairData extends Data
{
    public function __construct(
        public readonly string $publicKey,
        public readonly string $secretKey,
    ) {
    }
}
