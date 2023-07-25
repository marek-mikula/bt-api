<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserNotification\MarkAsReadRequest;
use App\Http\Resources\NotificationPaginatedResourceCollection;
use App\Http\Resources\NotificationResource;
use App\Models\User;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserNotificationController extends Controller
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function index(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $notifications = $user->notifications()
            ->latest()
            ->paginate(10);

        return $this->sendSuccess([
            'notifications' => new NotificationPaginatedResourceCollection($notifications),
        ]);
    }

    public function unread(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $count = $this->notificationRepository->getUnreadNotificationsCount($user);

        return $this->sendSuccess([
            'count' => $count,
        ]);
    }

    public function markAsRead(MarkAsReadRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        if ($request->shouldMarkAll()) {
            $this->notificationRepository->markAllAsRead($user);

            return $this->sendSuccess();
        }

        $notification = $this->notificationRepository->markAsRead($request->getNotification());

        return $this->sendSuccess([
            'notification' => new NotificationResource($notification),
        ]);
    }
}
