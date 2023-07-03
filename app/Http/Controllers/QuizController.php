<?php

namespace App\Http\Controllers;

use App\Services\QuizService;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    public function __construct(private readonly QuizService $service)
    {
    }

    public function questions(): JsonResponse
    {
        $questions = $this->service->getQuestions();

        return $this->sendSuccess([
            'questions' => $questions->toArray(),
        ]);
    }
}
