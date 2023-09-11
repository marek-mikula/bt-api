<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $symbol
 * @property string $name
 * @property bool $is_fiat
 * @property int|null $coinmarketcap_id
 * @property array $meta
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
        'meta' => 'array',
    ];
}
