<?php

namespace App\Notifications\User;

use App\Mail\User\ResetPasswordMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

class ResetPasswordNotification extends BaseNotification
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

    public function toMail(User $notifiable): ResetPasswordMail
    {
        return new ResetPasswordMail($notifiable, $this->mfaToken);
    }
}
