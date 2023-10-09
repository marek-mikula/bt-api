<?php

namespace App\Models\Query\Traits;

use App\Models\Query\BaseQuery;
use App\Models\User;

/**
 * @mixin BaseQuery
 */
trait BelongsToUser
{
    public function ofUser(User|int $user, string $column = 'user_id'): static
    {
        return $this->ofUserId($user instanceof User ? $user->id : $user, $column);
    }

    public function ofUserId(int $userId, string $column = 'user_id'): static
    {
        return $this->where($column, '=', $userId);
    }
}
