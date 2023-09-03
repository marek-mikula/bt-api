<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\User\Http\Requests\UpdateLimitsRequest;
use Domain\User\Services\UserLimitsSettingsService;
use Illuminate\Http\JsonResponse;

class UserLimitsSettingsController extends ApiController
{
    public function __construct(
        private readonly UserLimitsSettingsService $service,
    ) {
    }

    public function show(): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }

    public function update(UpdateLimitsRequest $request): JsonResponse
    {
        $user = $request->user('api');

        $this->service->update($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
