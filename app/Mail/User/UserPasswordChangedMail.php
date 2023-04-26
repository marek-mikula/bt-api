<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class UserPasswordChangedMail extends BaseMail
{
    public function __construct(
        private readonly User $user,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: 'Password changed'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.user.password-changed',
            with: [
                'user' => $this->user,
            ]
        );
    }

    /**
     * @return list<Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
