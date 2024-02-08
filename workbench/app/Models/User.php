<?php

namespace Workbench\App\Models;

use Creasi\Base\Models\Concerns\WithDevices;
use Creasi\Base\Models\Concerns\WithIdentity;
use Creasi\Base\Models\Contracts\HasIdentity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;
use Workbench\Database\Factories\UserFactory;

/**
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 *
 * @method static UserFactory<static> factory()
 */
class User extends Authenticatable implements HasApiTokensContract, HasIdentity
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use WithDevices;
    use WithIdentity;

    protected $fillable = ['name', 'email', 'password'];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function password(): Attribute
    {
        return Attribute::set(fn (string $value) => \bcrypt($value));
    }
}
