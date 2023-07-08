<?php

namespace App\Repositories\QuizResult;

use App\Models\QuizResult;

interface QuizResultRepositoryInterface
{
    public function create(array $data): QuizResult;
}
