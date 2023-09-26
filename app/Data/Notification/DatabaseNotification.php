<?php

namespace App\Data\Notification;

use App\Data\BaseData;
use App\Enums\NotificationTypeEnum;
use InvalidArgumentException;

class DatabaseNotification extends BaseData
{
    public string $title = '';

    public ?string $body = null;

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

    public function when(bool $bool, callable $callback): self
    {
        if ($bool) {
            $callback($this);
        }

        return $this;
    }

    public function toArray(): array
    {
        if (empty($this->title)) {
            throw new InvalidArgumentException('Cannot save notification with empty title.');
        }

        // do not include empty body to final array data
        if (empty($this->body)) {
            $this->except('body');
        }

        // do not include empty input to final array data
        if (empty($this->input)) {
            $this->except('input');
        }

        return parent::toArray();
    }
}
