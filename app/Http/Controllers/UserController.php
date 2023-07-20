<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function unreadNotifications(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $count = $this->userRepository->unreadNotificationsCount($user);

        return $this->sendSuccess([
            'count' => $count,
        ]);
    }
}
