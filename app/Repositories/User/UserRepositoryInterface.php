<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function verifyEmail(User $user): User;
}