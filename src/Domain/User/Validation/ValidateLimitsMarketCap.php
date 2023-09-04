<?php

namespace Domain\User\Validation;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateLimitsMarketCap
{
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $enabled = (bool) Arr::get($data, 'marketCap.enabled', false);

        if (! $enabled) {
            return;
        }

        $totalValue = $this->getTotalValue($data);

        if ($totalValue <= 100 && $totalValue >= 0) {
            return;
        }

        // todo
        $validator->addFailure('publicKey', 'validity');
    }

    private function getTotalValue(array $data): int
    {
        $inputs = [
            'marketCap.micro',
            'marketCap.small',
            'marketCap.mid',
            'marketCap.large',
            'marketCap.mega',
        ];

        $value = 0;

        foreach ($inputs as $input) {
            $value += (int) Arr::get($data, $input, 0);
        }

        return $value;
    }
}
