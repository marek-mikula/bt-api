<?php

namespace Domain\User\Services;

use App\Http\Requests\AuthRequest;
use Domain\Auth\Services\AuthService;

class UserService
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function delete(AuthRequest $request): void
    {
        $user = $request->user('api');

        $this->authService->logout($request);

        $user->delete();
    }
}
