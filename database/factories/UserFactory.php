<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'public_key' => 'PUBLIC_KEY',
            'secret_key' => 'SECRET_KEY',
            'remember_token' => null,
            'email_verified_at' => null,
            'quiz_finished_at' => null,
        ];
    }

    public function quizTaken(?Carbon $at = null): self
    {
        return $this->state([
            'quiz_finished_at' => $at ?? Carbon::now(),
        ]);
    }

    public function emailVerified(?Carbon $at = null): self
    {
        return $this->state([
            'email_verified_at' => $at ?? Carbon::now(),
        ]);
    }
}
