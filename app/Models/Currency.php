<?php

namespace App\Models;

use App\Enums\CurrencyStateEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property CurrencyStateEnum $state
 * @property string $symbol
 * @property string $name
 * @property bool $is_fiat
 * @property int|null $coinmarketcap_id
 * @property array $meta
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<Asset> $assets
 */
class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $primaryKey = 'id';

    protected $fillable = [
        'state',
        'symbol',
        'name',
        'is_fiat',
        'coinmarketcap_id',
        'meta',
    ];

    protected $attributes = [
        'is_fiat' => false,
    ];

    protected $casts = [
        'state' => CurrencyStateEnum::class,
        'symbol' => 'string',
        'name' => 'string',
        'is_fiat' => 'boolean',
        'coinmarketcap_id' => 'integer',
        'meta' => 'array',
    ];

    /**
     * @see Currency::$assets
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'currency_id', 'id');
    }
}
