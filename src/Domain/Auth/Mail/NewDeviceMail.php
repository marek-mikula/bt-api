<?php

namespace Domain\Auth\Mail;

use App\Enums\NotificationTypeEnum;
use App\Formatters\DateTimeFormatter;
use App\Mail\BaseMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class NewDeviceMail extends BaseMail
{
    use DateTimeFormatter;

    public function __construct(
        private readonly User $user,
        private readonly AuthenticationLog $log
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                $this->user->email
            ],
            subject: __n(NotificationTypeEnum::NEW_DEVICE, 'mail', 'subject')
        );
    }

    public function content(): Content
    {
        /** @var Carbon $time */
        $time = $this->log->getAttribute('login_at');

        return new Content(
            markdown: 'auth::mail.new-device',
            with: [
                'user' => $this->user,
                'time' => $this->formatDatetime($time),
                'ipAddress' => $this->log->getAttribute('ip_address'),
                'browser' => $this->log->getAttribute('user_agent'),
                //                'location' => $this->log->getAttribute('location'), we are not using geoip
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
