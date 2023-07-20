<?php

namespace App\Models;

use App\Notifications\BaseNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Arr;

/**
 * @property-read string $id uuid string ID
 * @property class-string<BaseNotification> $type
 * @property class-string<Model> $notifiable
 * @property array $data
 * @property-read string $_title
 * @property-read string $_body
 * @property-read string $_type
 * @property-read string $_domain
 * @property-read array $_data
 * @property Carbon|null $read_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends DatabaseNotification
{
    use HasFactory;

    /**
     * @see Notification::$_title
     */
    protected function _title(): Attribute
    {
        return Attribute::get(fn (): string => (string) Arr::get($this->data, 'title'));
    }

    /**
     * @see Notification::$_body
     */
    protected function _body(): Attribute
    {
        return Attribute::get(fn (): string => (string) Arr::get($this->data, 'body'));
    }

    /**
     * @see Notification::$_type
     */
    protected function _type(): Attribute
    {
        return Attribute::get(fn (): string => (string) Arr::get($this->data, 'type'));
    }

    /**
     * @see Notification::$_domain
     */
    protected function _domain(): Attribute
    {
        return Attribute::get(fn (): string => (string) Arr::get($this->data, 'domain'));
    }

    /**
     * @see Notification::$_input
     */
    protected function _input(): Attribute
    {
        return Attribute::get(fn (): string => Arr::get($this->data, 'input', []));
    }
}
