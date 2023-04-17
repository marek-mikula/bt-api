<?php

namespace App\Mail\User;

use App\Mail\BaseMail;
use App\Models\MfaToken;
use App\Models\User;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class UserVerifyDeviceMail extends BaseMail
{
    public function __construct(
        private readonly User $user,
        private readonly MfaToken $mfaToken,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: 'Please verify your new device'
        );
    }

    public function content(): Content
    {
        $frontEndUrl = config('app.frontend_url');

        // create URL address to frontend app
        $url = vsprintf('%s/mfa/verify-device?token=%s', [
            $frontEndUrl,
            $this->mfaToken->secret_token,
        ]);

        return new Content(
            markdown: 'mail.user.verify-device',
            with: [
                'user' => $this->user,
                'url' => $url,
                'code' => $this->mfaToken->code,
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
