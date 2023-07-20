<?php

namespace App\Notifications\User;

use App\Mail\User\VerifyEmailMail;
use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;

class VerifyEmailNotification extends BaseNotification
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

    public function toMail(User $notifiable): VerifyEmailMail
    {
        return new VerifyEmailMail($notifiable, $this->mfaToken);
    }
}
