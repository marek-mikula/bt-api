<?php

namespace App\Data\Notification;

use App\Enums\NotificationTypeEnum;
use InvalidArgumentException;
use Spatie\LaravelData\Data;

class DatabaseNotification extends Data
{
    public string $title = '';

    public string $body = '';

    public array $input = [];

    private function __construct(
        public readonly NotificationTypeEnum $type,
    ) {
    }

    public static function create(NotificationTypeEnum $type): self
    {
        return new self(type: $type);
    }

    public function title(string $key, array $replace = [], string $locale = null): self
    {
        $this->title = __n($this->type, 'database', $key, $replace, $locale);

        return $this;
    }

    public function body(string $key, array $replace = [], string $locale = null): self
    {
        $this->body = __n($this->type, 'database', $key, $replace, $locale);

        return $this;
    }

    public function input(array $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->title) || empty($this->body)) {
            throw new InvalidArgumentException('Cannot save notification with empty title or body.');
        }

        return parent::toArray();
    }
}
