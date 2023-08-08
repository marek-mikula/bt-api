<?php

namespace Domain\User\Http\Requests;

use App\Http\Requests\AuthRequest;
use App\Models\Notification;
use App\Repositories\Notification\NotificationRepositoryInterface;
use LogicException;

class MarkAsReadRequest extends AuthRequest
{
    private ?Notification $notification = null;

    public function authorize(): bool
    {
        // user wants to mark all notifications
        // as read => no need to check if
        // notification is his
        if ($this->shouldMarkAll()) {
            return true;
        }

        /** @var NotificationRepositoryInterface $notificationRepository */
        $notificationRepository = app(NotificationRepositoryInterface::class);

        $user = $this->user('api');

        $notification = $notificationRepository->find($this->getUuid(), $user);

        if (! $notification?->is_unread) {
            return false;
        }

        // set notification to request, so we don't have
        // to query it again in the controlled
        $this->notification = $notification;

        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'nullable',
                'string',
                'uuid',
            ],
        ];
    }

    /**
     * If the request does not have single UUID to mark as
     * read, we suppose the user wants to mark all unread
     * notifications as read.
     */
    public function shouldMarkAll(): bool
    {
        return ! $this->has('uuid');
    }

    public function getUuid(): ?string
    {
        return $this->shouldMarkAll() ? null : $this->string('uuid');
    }

    public function getNotification(): Notification
    {
        if (empty($this->notification)) {
            throw new LogicException('Cannot retrieve empty notification.');
        }

        return $this->notification;
    }
}
