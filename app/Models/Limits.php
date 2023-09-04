<?php

namespace App\Models;

use Carbon\Carbon;
use Domain\User\Enums\LimitsNotificationPeriodEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $user_id
 * @property bool $trade_enabled
 * @property int|null $trade_daily
 * @property int|null $trade_weekly
 * @property int|null $trade_monthly
 * @property bool $cryptocurrency_enabled
 * @property LimitsNotificationPeriodEnum|null $cryptocurrency_period
 * @property int|null $cryptocurrency_min
 * @property int|null $cryptocurrency_max
 * @property bool $market_cap_enabled
 * @property LimitsNotificationPeriodEnum|null $market_cap_period
 * @property int|null $market_cap_margin
 * @property bool $market_cap_micro_enabled
 * @property int|null $market_cap_micro
 * @property bool $market_cap_small_enabled
 * @property int|null $market_cap_small
 * @property bool $market_cap_mid_enabled
 * @property int|null $market_cap_mid
 * @property bool $market_cap_large_enabled
 * @property int|null $market_cap_large
 * @property bool $market_cap_mega_enabled
 * @property int|null $market_cap_mega
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 */
class Limits extends Model
{
    use HasFactory;

    protected $table = 'limits';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'trade_enabled',
        'trade_daily',
        'trade_weekly',
        'trade_monthly',
        'cryptocurrency_enabled',
        'cryptocurrency_period',
        'cryptocurrency_min',
        'cryptocurrency_max',
        'market_cap_enabled',
        'market_cap_period',
        'market_cap_margin',
        'market_cap_micro_enabled',
        'market_cap_micro',
        'market_cap_small_enabled',
        'market_cap_small',
        'market_cap_mid_enabled',
        'market_cap_mid',
        'market_cap_large_enabled',
        'market_cap_large',
        'market_cap_mega_enabled',
        'market_cap_mega',
    ];

    protected $attributes = [
        'trade_enabled' => false,
        'cryptocurrency_enabled' => false,
        'market_cap_enabled' => false,
        'market_cap_micro_enabled' => false,
        'market_cap_small_enabled' => false,
        'market_cap_mid_enabled' => false,
        'market_cap_large_enabled' => false,
        'market_cap_mega_enabled' => false,
    ];

    protected $casts = [
        'user_id' => 'integer',
        'trade_enabled' => 'boolean',
        'trade_daily' => 'integer',
        'trade_weekly' => 'integer',
        'trade_monthly' => 'integer',
        'cryptocurrency_enabled' => 'boolean',
        'cryptocurrency_period' => LimitsNotificationPeriodEnum::class,
        'cryptocurrency_min' => 'integer',
        'cryptocurrency_max' => 'integer',
        'market_cap_enabled' => 'boolean',
        'market_cap_period' => LimitsNotificationPeriodEnum::class,
        'market_cap_margin' => 'integer',
        'market_cap_micro_enabled' => 'boolean',
        'market_cap_micro' => 'integer',
        'market_cap_small_enabled' => 'boolean',
        'market_cap_small' => 'integer',
        'market_cap_mid_enabled' => 'boolean',
        'market_cap_mid' => 'integer',
        'market_cap_large_enabled' => 'boolean',
        'market_cap_large' => 'integer',
        'market_cap_mega_enabled' => 'boolean',
        'market_cap_mega' => 'integer',
    ];

    public function canBeUpdated(): bool
    {
        // if created_at and updated_at equals, the
        // models has just been created
        return $this->created_at->eq($this->updated_at) || $this->getResetTime()->isPast();
    }

    public function getResetTime(): Carbon
    {
        return $this->updated_at->addDays(3);
    }

    /**
     * @see Limits::$user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
