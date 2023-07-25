<?php

namespace App\Notifications\User;

use App\Mail\User\RegisteredMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

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
