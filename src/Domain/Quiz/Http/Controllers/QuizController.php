<?php

namespace Domain\Quiz\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\Quiz\Http\Requests\FinishRequest;
use Domain\Quiz\Services\QuizService;
use Illuminate\Http\JsonResponse;

class QuizController extends ApiController
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
        $user = $request->user('api');

        $this->service->finish($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
