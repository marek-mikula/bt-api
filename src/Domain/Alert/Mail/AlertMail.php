<?php

namespace Domain\Alert\Mail;

use App\Enums\NotificationTypeEnum;
use App\Mail\BaseMail;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\Attributes\WithoutRelations;

class AlertMail extends BaseMail
{
    public function __construct(
        #[WithoutRelations]
        private readonly User $user,
        #[WithoutRelations]
        private readonly Alert $alert,
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                $this->user->email,
            ],
            subject: __n(NotificationTypeEnum::ALERT, 'mail', 'subject', [
                'title' => $this->alert->title,
            ])
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'alert::mail.alert',
            with: [
                'user' => $this->user,
                'alert' => $this->alert,
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
