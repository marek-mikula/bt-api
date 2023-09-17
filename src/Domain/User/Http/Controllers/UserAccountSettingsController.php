<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\User\Http\Requests\SaveAccountKeysSettingsRequest;
use Domain\User\Http\Requests\SaveAccountPasswordSettingsRequest;
use Domain\User\Http\Requests\SaveAccountPersonalSettingsRequest;
use Domain\User\Services\UserAccountSettingsService;
use Illuminate\Http\JsonResponse;

class UserAccountSettingsController extends ApiController
{
    public function __construct(
        private readonly UserAccountSettingsService $service,
    ) {
    }

    public function savePersonal(SaveAccountPersonalSettingsRequest $request): JsonResponse
    {
        $user = $request->user('api');

        $this->service->savePersonal($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }

    public function savePassword(SaveAccountPasswordSettingsRequest $request): JsonResponse
    {
        $user = $request->user('api');

        $this->service->savePassword($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }

    public function saveKeys(SaveAccountKeysSettingsRequest $request): JsonResponse
    {
        $user = $request->user('api');

        $this->service->saveKeys($user, $request->toData());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
