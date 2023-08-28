<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\User\Http\Requests\SaveLimitsRequest;
use Domain\User\Http\Requests\SaveNotificationsRequest;
use Illuminate\Http\JsonResponse;

class UserSettingsController extends ApiController
{
    public function __construct()
    {
    }

    public function saveNotifications(SaveNotificationsRequest $request): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [

        ]);
    }

    public function saveLimits(SaveLimitsRequest $request): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [

        ]);
    }
}
