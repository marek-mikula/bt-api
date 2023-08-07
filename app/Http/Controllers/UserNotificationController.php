<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCodeEnum;
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
        $page = $request->integer('page', 1);

        /** @var User $user */
        $user = $request->user('api');

        $notifications = $user->notifications()
            ->latest()
            ->paginate(
                perPage: 10,
                page: $page
            );

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'notifications' => new NotificationPaginatedResourceCollection($notifications),
        ]);
    }

    public function unread(AuthRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        $count = $this->notificationRepository->getUnreadNotificationsCount($user);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'count' => $count,
        ]);
    }

    public function markAsRead(MarkAsReadRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user('api');

        if ($request->shouldMarkAll()) {
            $this->notificationRepository->markAllAsRead($user);

            return $this->sendJsonResponse(code: ResponseCodeEnum::OK);
        }

        $notification = $this->notificationRepository->markAsRead($request->getNotification());

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'notification' => new NotificationResource($notification),
        ]);
    }
}
