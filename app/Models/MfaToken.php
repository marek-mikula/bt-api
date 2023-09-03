<?php

namespace App\Models;

use App\Models\Formatters\MfaTokenFormatter;
use App\Models\Query\MfaTokenQuery;
use Carbon\Carbon;
use Database\Factories\MfaTokenFactory;
use Domain\Auth\Enums\MfaTokenTypeEnum;
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
 * @property-read bool $invalidated
 * @property-read bool $is_expired
 * @property Carbon $valid_until
 * @property Carbon|null $invalidated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 *
 * @method static MfaTokenQuery query()
 * @method static MfaTokenFactory factory($count = null, $state = [])
 */
class MfaToken extends Model
{
    use HasFactory;
    use MfaTokenFormatter;

    protected $table = 'mfa_tokens';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'token',
        'code',
        'type',
        'valid_until',
        'invalidated_at',
    ];

    protected $hidden = [
        'token',
        'code',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'token' => 'string',
        'code' => 'string',
        'type' => MfaTokenTypeEnum::class,
        'valid_until' => 'datetime',
        'invalidated_at' => 'datetime',
    ];

    /**
     * @see MfaToken::$is_expired
     */
    protected function isExpired(): Attribute
    {
        return Attribute::get(function (): bool {
            return $this->invalidated || $this->valid_until->lte(now());
        });
    }

    /**
     * @see MfaToken::$invalidated
     */
    protected function invalidated(): Attribute
    {
        return Attribute::get(fn (): bool => ! empty($this->invalidated_at));
    }

    /**
     * @see MfaToken::$code
     */
    protected function code(): Attribute
    {
        return Attribute::set(static fn (string $value): string => Str::lower($value));
    }

    /**
     * @see MfaToken::$secret_token
     */
    protected function secretToken(): Attribute
    {
        return Attribute::get(fn (): string => Crypt::encryptString($this->token));
    }

    /**
     * @see MfaToken::$user
     */
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

    /**
     * @see MfaToken::factory()
     */
    protected static function newFactory(): MfaTokenFactory
    {
        return new MfaTokenFactory();
    }
}
