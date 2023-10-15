<?php

namespace App\Models;

use App\Models\Query\CurrencyQuery;
use Carbon\Carbon;
use Domain\Currency\Enums\MarketCapCategoryEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property string $symbol
 * @property string $name
 * @property bool $is_fiat
 * @property int $cmc_id
 * @property int|null $cmc_rank
 * @property MarketCapCategoryEnum|null $market_cap_category
 * @property array $meta
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<Asset> $assets
 * @property-read Collection<Currency> $quoteCurrencies
 *
 * @method static CurrencyQuery query()
 */
class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $primaryKey = 'id';

    protected $fillable = [
        'symbol',
        'name',
        'is_fiat',
        'cmc_id',
        'cmc_rank',
        'market_cap_category',
        'meta',
    ];

    protected $attributes = [
        'is_fiat' => false,
    ];

    protected $casts = [
        'symbol' => 'string',
        'name' => 'string',
        'is_fiat' => 'boolean',
        'cmc_id' => 'integer',
        'cmc_rank' => 'integer',
        'market_cap_category' => MarketCapCategoryEnum::class,
        'meta' => 'array',
    ];

    /**
     * @see Currency::$assets
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'currency_id', 'id');
    }

    /**
     * @see Currency::$quoteCurrencies
     */
    public function quoteCurrencies(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Currency::class,
            table: 'currency_pairs',
            foreignPivotKey: 'base_currency_id',
            relatedPivotKey: 'quote_currency_id',
            parentKey: 'id',
            relatedKey: 'id',
        )->withPivot([
            'symbol',
            'min_quantity',
            'max_quantity',
            'step_size',
        ]);
    }

    /**
     * @param  Builder  $query
     *
     * @see Currency::query()
     */
    public function newEloquentBuilder($query): CurrencyQuery
    {
        return new CurrencyQuery($query);
    }
}
