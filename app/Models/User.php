<?php

namespace App\Models;

use App\Casts\EncryptCast;
use App\Enums\MfaTokenTypeEnum;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property-read int $id
 * @property string $firstname
 * @property string $lastname
 * @property-read string $full_name
 * @property string $email
 * @property string $password
 * @property string $public_key
 * @property string $secret_key
 * @property-read boolean $quiz_taken
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $quiz_finished_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<RefreshToken> $refreshTokens
 * @property-read Collection<MfaToken> $mfaTokens
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'public_key',
        'secret_key',
        'email_verified_at',
        'quiz_finished_at',
    ];

    protected $hidden = [
        'password',
        'public_key',
        'secret_key',
    ];

    protected $casts = [
        'public_key' => EncryptCast::class,
        'secret_key' => EncryptCast::class,
        'mfa_token_type' => MfaTokenTypeEnum::class,
        'mfa_token_until' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    protected function password(): Attribute
    {
        return Attribute::set(static fn (string $value): string => Hash::make($value));
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => collect([
            $this->firstname,
            $this->lastname,
        ])->filter()->implode(' '));
    }

    protected function quizTaken(): Attribute
    {
        return Attribute::get(fn (): bool => ! empty($this->quiz_finished_at));
    }

    public function refreshTokens(): HasMany
    {
        return $this->hasMany(RefreshToken::class, 'user_id', 'id');
    }

    public function mfaTokens(): HasMany
    {
        return $this->hasMany(MfaToken::class, 'user_id', 'id');
    }

    public function getJWTIdentifier(): int
    {
        return $this->id;
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    protected static function newFactory(): UserFactory
    {
        return new UserFactory();
    }
}
