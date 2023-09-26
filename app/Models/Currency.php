<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read int $id
 * @property string $symbol
 * @property string $name
 * @property bool $is_fiat
 * @property int $coinmarketcap_id
 * @property int|null $cmc_rank
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
        'symbol',
        'name',
        'is_fiat',
        'coinmarketcap_id',
        'cmc_rank',
        'meta',
    ];

    protected $attributes = [
        'is_fiat' => false,
    ];

    protected $casts = [
        'symbol' => 'string',
        'name' => 'string',
        'is_fiat' => 'boolean',
        'coinmarketcap_id' => 'integer',
        'cmc_rank' => 'integer',
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
