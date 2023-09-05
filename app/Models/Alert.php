<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $user_id
 * @property bool $as_mail
 * @property bool $as_notification
 * @property string $title
 * @property string|null $content
 * @property Carbon $date_at
 * @property Carbon|null $time_at
 * @property Carbon|null $notified_at
 * @property-read bool $was_notified
 * @property Carbon|null $queued_at
 * @property-read bool $was_queued
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class Alert extends Model
{
    use HasFactory;

    protected $table = 'alerts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'as_mail',
        'as_notification',
        'title',
        'content',
        'date_at',
        'time_at',
        'notified_at',
        'queued_at',
    ];

    protected $attributes = [
        'as_mail' => false,
        'as_notification' => false,
    ];

    protected $casts = [
        'user_id' => 'integer',
        'as_mail' => 'boolean',
        'as_notification' => 'boolean',
        'title' => 'string',
        'content' => 'string',
        'date_at' => 'datetime:Y-m-d',
        'time_at' => 'datetime:H:i',
        'notified_at' => 'datetime:Y-m-d H:i:s',
        'queued_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @see Alert::$was_queued
     */
    public function wasQueued(): Attribute
    {
        return Attribute::get(fn (): bool => ! empty($this->queued_at));
    }

    /**
     * @see Alert::$was_notified
     */
    public function wasNotified(): Attribute
    {
        return Attribute::get(fn (): bool => ! empty($this->notified_at));
    }

    /**
     * @see Alert::$user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}