<?php

namespace Workbench\App\Models;

use Creasi\Base\Contracts\HasCredentialTokens;
use Creasi\Base\Database\Models\Concerns\WithCredentialTokens;
use Creasi\Base\Database\Models\Concerns\WithDevices;
use Creasi\Base\Database\Models\Concerns\WithIdentity;
use Creasi\Base\Database\Models\Contracts\HasDevices;
use Creasi\Base\Database\Models\Contracts\HasIdentity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Workbench\Database\Factories\UserFactory;

/**
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 *
 * @method static UserFactory<static> factory()
 */
class User extends Authenticatable implements HasCredentialTokens, HasDevices, HasIdentity
{
    use HasFactory;
    use Notifiable;
    use WithCredentialTokens;
    use WithDevices;
    use WithIdentity;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'immutable_datetime',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function password(): Attribute
    {
        return Attribute::set(fn (string $value) => \bcrypt($value));
    }
}
