<?php

namespace App\Rules;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AgeRule implements ValidationRule
{
    public function __construct(private readonly int $age)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        try {
            $value = Carbon::make($value)->startOfDay();
        } catch (InvalidFormatException) {
            return;
        }

        $passes = Carbon::now()
            ->startOfDay()
            ->subYears($this->age)
            ->gte($value);

        if ($passes) {
            return;
        }

        $fail('validation.age')->translate([
            'age' => $this->age,
        ]);
    }
}
