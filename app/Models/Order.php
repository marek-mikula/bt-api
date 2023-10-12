<?php

namespace App\Models;

use App\Models\Query\OrderQuery;
use Carbon\Carbon;
use Domain\Cryptocurrency\Enums\OrderStatusEnum;
use Domain\Cryptocurrency\Enums\OrderTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property string $binance_uuid
 * @property int $user_id
 * @property int $pair_id
 * @property OrderTypeEnum $type
 * @property OrderStatusEnum $status
 * @property float $quantity
 * @property float $quote_quantity
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read CurrencyPair $pair
 *
 * @method static OrderQuery query()
 */
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id';

    protected $fillable = [
        'binance_uuid',
        'user_id',
        'pair_id',
        'type',
        'status',
        'quantity',
        'quote_quantity',
        'price',
    ];

    protected $casts = [
        'binance_uuid' => 'string',
        'user_id' => 'integer',
        'pair_id' => 'integer',
        'type' => OrderTypeEnum::class,
        'status' => OrderStatusEnum::class,
        'quantity' => 'float',
        'quote_quantity' => 'float',
        'price' => 'float',
    ];

    /**
     * @see Order::$user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @see Order::$pair
     */
    public function pair(): BelongsTo
    {
        return $this->belongsTo(CurrencyPair::class, 'pair_id', 'id');
    }

    /**
     * @param  Builder  $query
     *
     * @see Order::query()
     */
    public function newEloquentBuilder($query): OrderQuery
    {
        return new OrderQuery($query);
    }
}
