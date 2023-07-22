<?php

namespace App\Notifications\User;

use App\Data\Notification\DatabaseNotification;
use App\Enums\NotificationTypeEnum;
use App\Formatters\DateTimeFormatter;
use App\Mail\User\NewDeviceMail;
use App\Models\User;
use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class NewDeviceNotification extends BaseNotification
{
    use DateTimeFormatter;

    public function __construct(private readonly AuthenticationLog $authenticationLog)
    {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
            'database',
        ];
    }

    public function toMail(User $notifiable): NewDeviceMail
    {
        return new NewDeviceMail($notifiable, $this->authenticationLog);
    }

    public function toDatabase(User $notifiable): array
    {
        /** @var Carbon $time */
        $time = $this->authenticationLog->getAttribute('login_at');

        return DatabaseNotification::create(NotificationTypeEnum::NEW_DEVICE)
            ->title('title')
            ->body('body', [
                'ipAddress' => $this->authenticationLog->getAttribute('ip_address'),
                'browser' => $this->authenticationLog->getAttribute('user_agent'),
                'time' => $this->formatDatetime($time),
            ])
            ->input([
                'ipAddress' => $this->authenticationLog->getAttribute('ip_address'),
                'browser' => $this->authenticationLog->getAttribute('user_agent'),
                'time' => $time->toIso8601String(),
            ])
            ->toArray();
    }
}
