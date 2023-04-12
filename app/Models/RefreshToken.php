<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $refresh_token
 * @property string $device
 * @property bool $invalidated
 * @property Carbon $valid_until
 * @property Carbon|null $invalidated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class RefreshToken extends Model
{
    use HasFactory;

    protected $table = 'refresh_tokens';

    protected $primaryKey = 'id';

    protected $attributes = [
        'invalidated' => false,
        'invalidated_at' => null,
    ];

    protected $fillable = [
        'user_id',
        'refresh_token',
        'device',
        'valid_until',
    ];

    protected $hidden = [
        'refresh_token',
    ];

    protected $casts = [
        'invalidated' => 'boolean',
        'valid_until' => 'datetime',
        'invalidated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
