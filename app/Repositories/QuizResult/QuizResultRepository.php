<?php

namespace App\Repositories\QuizResult;

use App\Models\QuizResult;

class QuizResultRepository implements QuizResultRepositoryInterface
{
    public function create(array $data): QuizResult
    {
        /** @var QuizResult $quizResult */
        $quizResult = QuizResult::query()->create($data);

        return $quizResult;
    }
}
