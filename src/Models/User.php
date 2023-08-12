<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\HasIdentity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 *
 * @method static \Database\Factories\UserFactory<static> factory()
 */
class User extends Authenticatable implements HasIdentity
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'immutable_datetime',
    ];

    protected $appends = [];

    public function password(): Attribute
    {
        return Attribute::set(fn (string $value) => \bcrypt($value));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Personnel
     */
    public function identity()
    {
        return $this->hasOne(Personnel::class);
    }
}