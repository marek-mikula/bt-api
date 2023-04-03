<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property-read int $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'email_verified_at',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function password(): Attribute
    {
        return Attribute::set(static fn (string $value): string => Hash::make($value));
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
