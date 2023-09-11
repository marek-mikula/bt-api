<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $user_id
 * @property int $currency_id
 * @property float $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Currency $currency
 */
class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'currency_id',
        'balance',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'currency_id' => 'integer',
        'balance' => 'float',
    ];

    /**
     * @see Asset::$user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @see Asset::$currency
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
