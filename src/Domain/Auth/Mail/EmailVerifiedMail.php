<?php

namespace Domain\Auth\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EmailVerifiedMail extends BaseMail
{
    public function __construct(
        private readonly User $user,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                $this->user->email
            ],
            subject: __n(NotificationTypeEnum::EMAIL_VERIFIED, 'mail', 'subject')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'auth::mail.email-verified',
            with: [
                'user' => $this->user,
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
