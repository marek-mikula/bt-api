<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $currency
 * @property float $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'currency',
        'balance',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'currency' => 'string',
        'balance' => 'float',
    ];

    /**
     * @see Asset::$user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
