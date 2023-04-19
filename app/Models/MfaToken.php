<?php

namespace App\Models;

use App\Enums\MfaTokenTypeEnum;
use App\Query\MfaTokenQuery;
use Carbon\Carbon;
use Database\Factories\MfaTokenFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property int $user_id
 * @property string $token
 * @property-read string $secret_token
 * @property string $code 6 chars long secret code
 * @property MfaTokenTypeEnum $type
 * @property array<string,mixed> $data
 * @property bool $invalidated
 * @property Carbon $valid_until
 * @property Carbon|null $invalidated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 *
 * @method static MfaTokenQuery query()
 */
class MfaToken extends Model
{
    use HasFactory;

    protected $table = 'mfa_tokens';

    protected $primaryKey = 'id';

    protected $attributes = [
        'invalidated' => false,
        'invalidated_at' => null,
    ];

    protected $fillable = [
        'user_id',
        'token',
        'code',
        'type',
        'data',
        'invalidated',
        'valid_until',
        'invalidated_at',
    ];

    protected $hidden = [
        'token',
        'code',
    ];

    protected $casts = [
        'type' => MfaTokenTypeEnum::class,
        'data' => 'array',
        'invalidated' => 'boolean',
        'valid_until' => 'datetime',
        'invalidated_at' => 'datetime',
    ];

    public function code(): Attribute
    {
        return Attribute::set(static fn (string $value): string => Str::lower($value));
    }

    public function secretToken(): Attribute
    {
        return Attribute::get(fn (): string => Crypt::encryptString($this->token));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @param  Builder  $query
     *
     * @see MfaToken::query()
     */
    public function newEloquentBuilder($query): MfaTokenQuery
    {
        return new MfaTokenQuery($query);
    }

    protected static function newFactory(): MfaTokenFactory
    {
        return new MfaTokenFactory();
    }
}
