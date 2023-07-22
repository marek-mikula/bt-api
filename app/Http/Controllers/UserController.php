<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\User\MarkAsReadRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Carbon\Carbon;
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

    public function notifications(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $notifications = $user->notifications()
            ->latest()
            ->limit(20)
            ->get();

        return $this->sendSuccess([
            'notifications' => NotificationResource::collection($notifications),
        ]);
    }

    public function markAsRead(MarkAsReadRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        /** @var Notification $notification */
        $notification = $user->notifications()
            ->whereNull('read_at')
            ->whereKey($request->getUuid())
            ->firstOrFail();

        $notification->markAsRead();

        return $this->sendSuccess([
            'notification' => new NotificationResource($notification),
        ]);
    }

    public function markAllAsRead(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $query = $user->notifications()
            ->whereNull('read_at');

        if (! $query->exists()) {
            return $this->sendSuccess();
        }

        $query->update([
            'read_at' => Carbon::now(),
        ]);

        return $this->sendSuccess();
    }
}
