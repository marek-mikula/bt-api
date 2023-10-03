<?php

namespace App\Models\Query\Traits;

use App\Models\Query\BaseQuery;
use App\Models\User;

/**
 * @mixin BaseQuery
 */
trait BelongsToUser
{
    public function ofUser(User|int $user): static
    {
        return $this->ofUserId($user instanceof User ? $user->id : $user);
    }

    public function ofUserId(int $userId): static
    {
        return $this->where('user_id', '=', $userId);
    }
}
