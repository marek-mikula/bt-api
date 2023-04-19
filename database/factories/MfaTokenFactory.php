<?php

namespace Database\Factories;

use App\Enums\MfaTokenTypeEnum;
use App\Models\MfaToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MfaToken>
 */
class MfaTokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => Str::uuid()->toString(),
            'code' => Str::lower(Str::random(6)),
            'type' => $this->faker->randomElement(MfaTokenTypeEnum::cases())->value,
            'data' => [],
            'valid_until' => Carbon::now()->addDay(),
        ];
    }
}
