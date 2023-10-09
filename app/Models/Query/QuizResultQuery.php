<?php

namespace App\Models\Query;

use App\Models\Query\Traits\BelongsToUser;
use App\Models\QuizResult;

/**
 * @see QuizResult
 */
class QuizResultQuery extends BaseQuery
{
    use BelongsToUser;
}
