<?php

namespace Apis\Binance\Data;

use App\Data\BaseData;
use App\Models\User;

class KeyPairData extends BaseData
{
    public function __construct(
        public readonly string $publicKey,
        public readonly string $secretKey,
    ) {
    }

    public static function fromUser(User $user): self
    {
        return self::from([
            'publicKey' => $user->public_key,
            'secretKey' => $user->secret_key,
        ]);
    }

    public static function fromRaw(string $publicKey, string $secretKey): self
    {
        return self::from([
            'publicKey' => $publicKey,
            'secretKey' => $secretKey,
        ]);
    }

    public static function admin(): self
    {
        return self::from([
            'publicKey' => (string) config('binance.keys.public'),
            'secretKey' => (string) config('binance.keys.secret'),
        ]);
    }
}
