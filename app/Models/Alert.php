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
 * @property string $content
 * @property Carbon $date_at
 * @property Carbon|null $time_at
 * @property Carbon|null $notified_at
 * @property-read bool $was_notified
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
        'content',
        'date_at',
        'time_at',
        'notified_at',
    ];

    protected $casts = [
        'date_at' => 'datetime:Y-m-d',
        'time_at' => 'datetime:H:i',
        'notified_at' => 'datetime:Y-m-d H:i:s',
    ];

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
