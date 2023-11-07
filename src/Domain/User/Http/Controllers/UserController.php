<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use Domain\User\Http\Requests\DeleteAccountRequest;
use Domain\User\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends ApiController
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function delete(DeleteAccountRequest $request): JsonResponse
    {
        $this->userService->delete($request);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
    }
}
