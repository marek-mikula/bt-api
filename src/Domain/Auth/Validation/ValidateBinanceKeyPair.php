<?php

namespace Domain\Auth\Validation;

use Domain\Auth\Services\KeyValidator;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateBinanceKeyPair
{
    public function __construct(
        private readonly KeyValidator $keyValidator,
    ) {
    }

    public function __invoke(Validator $validator): void
    {
        [$publicKey, $secretKey] = $this->retrieveKeyPair($validator->getData());

        if (empty($publicKey) || empty($secretKey)) {
            return;
        }

        $isValid = $this->keyValidator->validate($publicKey, $secretKey);

        if ($isValid) {
            return;
        }

        $validator->addFailure('publicKey', 'validity');
        $validator->addFailure('secretKey', 'validity');
    }

    private function retrieveKeyPair(array $data): array
    {
        return [
            Arr::get($data, 'publicKey'),
            Arr::get($data, 'secretKey'),
        ];
    }
}
