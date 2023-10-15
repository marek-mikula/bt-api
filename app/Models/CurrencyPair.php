<?php

namespace App\Models;

use App\Models\Query\CurrencyPairQuery;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property-read int $id
 * @property int $base_currency_id
 * @property int $quote_currency_id
 * @property string $symbol
 * @property float|null $min_quantity
 * @property float|null $max_quantity
 * @property float|null $step_size
 * @property float|null $min_notional
 * @property float|null $max_notional
 * @property int $base_currency_precision
 * @property int $quote_currency_precision
 * @property-read Currency $baseCurrency
 * @property-read Currency $quoteCurrency
 *
 * @method static CurrencyPairQuery query()
 */
class CurrencyPair extends Model
{
    use HasFactory;

    protected $table = 'currency_pairs';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'base_currency_id',
        'quote_currency_id',
        'symbol',
        'min_quantity',
        'max_quantity',
        'step_size',
        'min_notional',
        'max_notional',
        'base_currency_precision',
        'quote_currency_precision',
    ];

    protected $casts = [
        'base_currency_id' => 'integer',
        'quote_currency_id' => 'integer',
        'symbol' => 'string',
        'min_quantity' => 'double',
        'max_quantity' => 'double',
        'step_size' => 'double',
        'min_notional' => 'double',
        'max_notional' => 'double',
        'base_currency_precision' => 'integer',
        'quote_currency_precision' => 'integer',
    ];

    /**
     * @see CurrencyPair::$baseCurrency
     */
    public function baseCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'base_currency_id', 'id');
    }

    /**
     * @see CurrencyPair::$quoteCurrency
     */
    public function quoteCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'quote_currency_id', 'id');
    }

    /**
     * @param  Builder  $query
     *
     * @see CurrencyPair::query()
     */
    public function newEloquentBuilder($query): CurrencyPairQuery
    {
        return new CurrencyPairQuery($query);
    }
}
