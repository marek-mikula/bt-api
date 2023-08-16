<?php

namespace Domain\User\Http\Controllers;

use App\Enums\ResponseCodeEnum;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AuthRequest;
use App\Http\Resources\NotificationPaginatedResourceCollection;
use App\Http\Resources\NotificationResource;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Domain\User\Http\Requests\MarkAsReadRequest;
use Illuminate\Http\JsonResponse;

class UserNotificationController extends ApiController
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
    ) {
    }

    public function index(AuthRequest $request): JsonResponse
    {
        $page = $request->integer('page', 1);

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
        $user = $request->user('api');

        $count = $this->notificationRepository->getUnreadNotificationsCount($user);

        return $this->sendJsonResponse(code: ResponseCodeEnum::OK, data: [
            'count' => $count,
        ]);
    }

    public function markAsRead(MarkAsReadRequest $request): JsonResponse
    {
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
