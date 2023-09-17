<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\User\Http\Requests\UpdateNotificationsSettingsRequest;
use Domain\User\Services\UserNotificationsSettingsService;
use Illuminate\Http\JsonResponse;

class UserNotificationsSettingsController extends ApiController
{
    public function __construct(
        private readonly UserNotificationsSettingsService $service
    ) {
    }

    public function update(UpdateNotificationsSettingsRequest $request): JsonResponse
    {
        $this->service->update($request->user('api'), $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
