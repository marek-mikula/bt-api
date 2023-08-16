<?php

namespace Domain\Auth\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\BaseMail;
use App\Models\MfaToken;
use App\Models\User;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ResetPasswordMail extends BaseMail
{
    public function __construct(
        private readonly User $user,
        private readonly MfaToken $mfaToken,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                $this->user->email
            ],
            subject: __n(NotificationTypeEnum::RESET_PASSWORD, 'mail', 'subject')
        );
    }

    public function content(): Content
    {
        // create URL address to frontend app
        $url = frontend_link('mfa/password-reset?token=%s', [
            $this->mfaToken->secret_token,
        ]);

        return new Content(
            markdown: 'auth::mail.reset-password',
            with: [
                'user' => $this->user,
                'url' => $url,
                'code' => $this->mfaToken->code,
                'validity' => $this->mfaToken->formatValidUntil(),
            ]
        );
    }

    /**
     * @return Attachment[]
     */
    public function attachments(): array
    {
        return [];
    }
}
