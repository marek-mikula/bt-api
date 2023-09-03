<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class UserLimitsSettingsController extends ApiController
{
    public function __construct(
    ) {
    }

    public function show(): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }

    public function update(): JsonResponse
    {
        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
