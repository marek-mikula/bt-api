<?php

namespace Domain\User\Validation;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

class ValidateLimits
{
    /**
     * Validates that if user enabled one section then he actually
     * filled some data into the section (at least one field must
     * not be empty).
     */
    public function __invoke(Validator $validator): void
    {
        $data = $validator->getData();

        $tradeEnabled = (bool) Arr::get($data, 'trade.enabled');
        if ($tradeEnabled && ! $this->validateTrade($data)) {
            $validator->addFailure('trade.enabled', 'limits_section_filled');
        }

        $cryptocurrencyEnabled = (bool) Arr::get($data, 'cryptocurrency.enabled');
        if ($cryptocurrencyEnabled && ! $this->validateCryptocurrency($data)) {
            $validator->addFailure('cryptocurrency.enabled', 'limits_section_filled');
        }

        $marketCapEnabled = (bool) Arr::get($data, 'marketCap.enabled');
        if ($marketCapEnabled && ! $this->validateMarketCap($data)) {
            $validator->addFailure('marketCap.enabled', 'limits_section_filled');
        }
    }

    private function validateTrade(array $data): bool
    {
        return $this->hasAnyFilled($data, [
            'trade.daily',
            'trade.weekly',
            'trade.monthly',
        ]);
    }

    private function validateCryptocurrency(array $data): bool
    {
        return $this->hasAnyFilled($data, [
            'cryptocurrency.min',
            'cryptocurrency.max',
        ]);
    }

    private function validateMarketCap(array $data): bool
    {
        return
            $this->hasAnyTrue($data, [
                'marketCap.microEnabled',
                'marketCap.smallEnabled',
                'marketCap.midEnabled',
                'marketCap.largeEnabled',
                'marketCap.megaEnabled',
            ]) && $this->hasAnyFilled($data, [
                'marketCap.micro',
                'marketCap.small',
                'marketCap.mid',
                'marketCap.large',
                'marketCap.mega',
            ]);
    }

    /**
     * @param  string[]  $inputs
     */
    private function hasAnyTrue(array $data, array $inputs): bool
    {
        foreach ($inputs as $input) {
            if (! Arr::has($data, $input)) {
                continue;
            }

            $value = (bool) Arr::get($data, $input);

            if ($value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  string[]  $inputs
     */
    private function hasAnyFilled(array $data, array $inputs): bool
    {
        foreach ($inputs as $input) {
            if (! Arr::has($data, $input)) {
                continue;
            }

            $value = Arr::get($data, $input);

            if (is_numeric($value) || ! empty($value)) {
                return true;
            }
        }

        return false;
    }
}
