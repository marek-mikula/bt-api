<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function update(User $user, array $data): User;

    public function verifyEmail(User $user): User;

    public function findByEmail(string $email): ?User;

    public function changePassword(User $user, string $password): User;

    public function finishQuiz(User $user): User;
}
