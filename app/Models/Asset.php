<?php

namespace App\Models;

use App\Models\Query\AssetQuery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $user_id
 * @property int|null $currency_id
 * @property-read bool $is_supported If is not supported, the asset symbol will be saved in $currency_symbol
 * @property string|null $currency_symbol
 * @property float $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Currency|null $currency
 *
 * @method static AssetQuery query()
 */
class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'currency_id',
        'currency_symbol',
        'balance',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'currency_id' => 'integer',
        'currency_symbol' => 'string',
        'balance' => 'float',
    ];

    public function isSupported(): Attribute
    {
        return Attribute::get(fn (): bool => ! empty($this->currency_id));
    }

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

    /**
     * @param  Builder  $query
     *
     * @see Asset::query()
     */
    public function newEloquentBuilder($query): AssetQuery
    {
        return new AssetQuery($query);
    }
}
