<?php

namespace Domain\Auth\Notifications;

use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;
use Domain\Auth\Mail\RegisteredMail;

class RegisteredNotification extends BaseNotification
{
    public function __construct(private readonly MfaToken $mfaToken)
    {
        parent::__construct();
    }

    public function via(User $notifiable): array
    {
        return [
            'mail',
        ];
    }

    public function toMail(User $notifiable): RegisteredMail
    {
        return new RegisteredMail($notifiable, $this->mfaToken);
    }
}