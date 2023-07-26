<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Requests\Quiz\FinishRequest;
use App\Models\User;
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

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'questions' => $questions->toArray(),
        ]);
    }

    public function finish(FinishRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $this->service->finish($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
