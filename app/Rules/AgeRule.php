<?php

namespace App\Rules;

use Carbon\Carbon;
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

        $value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();

        $passes = now()
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
