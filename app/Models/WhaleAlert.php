<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $currency_id
 * @property string $hash
 * @property float $amount
 * @property float $amount_usd
 * @property string|null $sender_address
 * @property string|null $sender_name
 * @property string|null $receiver_address
 * @property string|null $receiver_name
 * @property Carbon|null $notified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Currency $currency
 */
class WhaleAlert extends Model
{
    use HasFactory;

    protected $table = 'whale_alerts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'currency_id',
        'hash',
        'amount',
        'amount_usd',
        'sender_address',
        'sender_name',
        'receiver_address',
        'receiver_name',
        'notified_at',
    ];

    protected $casts = [
        'currency_id' => 'integer',
        'hash' => 'string',
        'amount' => 'float',
        'amount_usd' => 'float',
        'sender_address' => 'string',
        'sender_name' => 'string',
        'receiver_address' => 'string',
        'receiver_name' => 'string',
        'notified_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @see Asset::$currency
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }
}
