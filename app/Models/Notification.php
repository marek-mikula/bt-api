<?php

namespace App\Models;

use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property-read string $id uuid string ID
 * @property class-string<BaseNotification> $type
 * @property class-string<Model> $notifiable
 * @property array $data
 * @property-read string $data_title
 * @property-read string $data_body
 * @property-read string $data_type
 * @property-read string $data_domain
 * @property-read array $data_input
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends DatabaseNotification
{
    use HasFactory;

    /**
     * @see Notification::$data_title
     */
    protected function dataTitle(): Attribute
    {
        return Attribute::get(fn (): string => (string) Arr::get($this->data, 'title'));
    }

    /**
     * @see Notification::$data_body
     */
    protected function dataBody(): Attribute
    {
        return Attribute::get(fn (): string => (string) Arr::get($this->data, 'body'));
    }

    /**
     * @see Notification::$data_type
     */
    protected function dataType(): Attribute
    {
        return Attribute::get(fn (): string => Str::after((string) Arr::get($this->data, 'type'), '@'));
    }

    /**
     * @see Notification::$data_domain
     */
    protected function dataDomain(): Attribute
    {
        return Attribute::get(fn (): string => Str::before((string) Arr::get($this->data, 'type'), '@'));
    }

    /**
     * @see Notification::$data_input
     */
    protected function dataInput(): Attribute
    {
        return Attribute::get(fn (): array => Arr::get($this->data, 'input', []));
    }
}
