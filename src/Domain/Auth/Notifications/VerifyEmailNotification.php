<?php

namespace Domain\Auth\Notifications;

use App\Models\MfaToken;
use App\Models\User;
use App\Notifications\BaseNotification;
use Domain\Auth\Mail\VerifyEmailMail;
use Illuminate\Queue\Attributes\WithoutRelations;

class VerifyEmailNotification extends BaseNotification
{
    public function __construct(
        #[WithoutRelations]
        private readonly MfaToken $mfaToken
    ) {
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
