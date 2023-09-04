<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\LimitsResource;
use Domain\User\Http\Requests\UpdateLimitsRequest;
use Domain\User\Services\UserLimitsSettingsService;
use Illuminate\Http\JsonResponse;

class UserLimitsSettingsController extends ApiController
{
    public function __construct(
        private readonly UserLimitsSettingsService $service,
    ) {
    }

    public function show(AuthRequest $request): JsonResponse
    {
        $limits = $this->service->show($request->user('api'));

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'limits' => new LimitsResource($limits),
            'lock' => [
                'enabled' => $limits->canBeUpdated(),
                'resetAt' => $limits->getResetTime()->toIso8601String(),
            ],
        ]);
    }

    public function update(UpdateLimitsRequest $request): JsonResponse
    {
        $user = $request->user('api');

        $limits = $this->service->update($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'limits' => new LimitsResource($limits),
            'lock' => [
                'enabled' => $limits->canBeUpdated(),
                'resetAt' => $limits->getResetTime()->toIso8601String(),
            ],
        ]);
    }
}
