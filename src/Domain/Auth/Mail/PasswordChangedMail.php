<?php

namespace Domain\Auth\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class PasswordChangedMail extends BaseMail
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                $this->user->email,
            ],
            subject: __n(NotificationTypeEnum::PASSWORD_CHANGED, 'mail', 'subject')
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'auth::mail.password-changed',
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
