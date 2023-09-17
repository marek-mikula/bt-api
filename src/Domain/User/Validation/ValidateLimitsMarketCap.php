<?php

namespace Domain\User\Validation;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateLimitsMarketCap
{
    /**
     * Validates that given market cap limitation values
     * has max. 100% together.
     */
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

        $validator->addFailure('marketCap.micro', 'limits_market_cap_value');
        $validator->addFailure('marketCap.small', 'limits_market_cap_value');
        $validator->addFailure('marketCap.mid', 'limits_market_cap_value');
        $validator->addFailure('marketCap.large', 'limits_market_cap_value');
        $validator->addFailure('marketCap.mega', 'limits_market_cap_value');
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
