<?php

namespace App\Repositories\QuizResult;

use App\Models\QuizResult;

interface QuizResultRepositoryInterface
{
    /**
     * @param  array<string,mixed>  $data
     */
    public function create(array $data): QuizResult;
}
