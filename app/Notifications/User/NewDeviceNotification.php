<?php

namespace App\Notifications\User;

use App\Formatters\DateTimeFormatter;
use App\Mail\User\NewDeviceMail;
use App\Models\User;
use App\Notifications\BaseNotification;
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
        ];
    }

    public function toMail(User $notifiable): NewDeviceMail
    {
        return new NewDeviceMail($notifiable, $this->authenticationLog);
    }
}
