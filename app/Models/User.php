<?php

namespace App\Models;

use App\Casts\EncryptCast;
use App\Models\Traits\Notifiable;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;

/**
 * @property-read int $id
 * @property string $firstname
 * @property string $lastname
 * @property Carbon $birth_date
 * @property-read string $full_name
 * @property string $email
 * @property string $password
 * @property string $public_key
 * @property string $secret_key
 * @property-read bool $quiz_taken
 * @property string|null $remember_token
 * @property Carbon|null $assets_synced_at
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $quiz_finished_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<MfaToken> $mfaTokens
 * @property-read Collection<Asset> $assets
 * @property-read QuizResult|null $quizResult
 * @property-read Limits $limits
 * @property-read int|null $assets_count ->withCount('assets')
 *
 * @method static UserFactory factory($count = null, $state = [])
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use AuthenticationLoggable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'firstname',
        'lastname',
        'birth_date',
        'email',
        'password',
        'public_key',
        'secret_key',
        'remember_token',
        'assets_synced_at',
        'email_verified_at',
        'quiz_finished_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'public_key',
        'secret_key',
    ];

    protected $casts = [
        'firstname' => 'string',
        'lastname' => 'string',
        'birth_date' => 'datetime:Y-m-d',
        'email' => 'string',
        'password' => 'string',
        'public_key' => EncryptCast::class,
        'secret_key' => EncryptCast::class,
        'remember_token' => 'string',
        'assets_synced_at' => 'datetime:Y-m-d H:i:s',
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'quiz_finished_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * @see User::$password
     */
    protected function password(): Attribute
    {
        return Attribute::set(static fn (string $value): string => Hash::make($value));
    }

    /**
     * @see User::$full_name
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => collect([
            $this->firstname,
            $this->lastname,
        ])->filter()->implode(' '));
    }

    /**
     * @see User::$quiz_taken
     */
    protected function quizTaken(): Attribute
    {
        return Attribute::get(fn (): bool => ! empty($this->quiz_finished_at));
    }

    /**
     * @see User::$mfa_tokens
     */
    public function mfaTokens(): HasMany
    {
        return $this->hasMany(MfaToken::class, 'user_id', 'id');
    }

    /**
     * @see User::$quizResult
     */
    public function quizResult(): HasOne
    {
        return $this->hasOne(QuizResult::class, 'user_id', 'id');
    }

    /**
     * @see User::$limits
     */
    public function limits(): HasOne
    {
        return $this->hasOne(Limits::class, 'user_id', 'id');
    }

    /**
     * @see User::$assets
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'user_id', 'id');
    }

    /**
     * @see User::factory()
     */
    protected static function newFactory(): UserFactory
    {
        return new UserFactory();
    }
}
