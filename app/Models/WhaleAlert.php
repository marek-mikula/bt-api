<?php

namespace App\Models;

use App\Models\Query\WhaleAlertQuery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

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
 * @property Carbon $transaction_at
 * @property-read Currency $currency
 *
 * @method static WhaleAlertQuery query()
 */
class WhaleAlert extends Model
{
    use HasFactory;

    public $timestamps = false;

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
        'transaction_at',
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
        'transaction_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @see Asset::$currency
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    /**
     * @param  Builder  $query
     *
     * @see WhaleAlert::query()
     */
    public function newEloquentBuilder($query): WhaleAlertQuery
    {
        return new WhaleAlertQuery($query);
    }
}
