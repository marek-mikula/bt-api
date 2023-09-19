<?php

namespace Domain\Auth\Validation;

use Apis\Binance\Services\BinanceKeyValidator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateBinanceKeyPair
{
    public function __construct(
        private readonly BinanceKeyValidator $keyValidator,
    ) {
    }

    public function __invoke(Validator $validator): void
    {
        [$publicKey, $secretKey] = $this->retrieveKeyPair($validator->getData());

        if (empty($publicKey) || empty($secretKey)) {
            return;
        }

        // do not call API when debug mode is enabled
        if (app()->hasDebugModeEnabled()) {
            return;
        }

        $isValid = $this->keyValidator->validate($publicKey, $secretKey);

        if ($isValid) {
            return;
        }

        $validator->addFailure('publicKey', 'binance_key_validity');
        $validator->addFailure('secretKey', 'binance_key_validity');
    }

    private function retrieveKeyPair(array $data): array
    {
        return [
            Arr::get($data, 'publicKey'),
            Arr::get($data, 'secretKey'),
        ];
    }
}
