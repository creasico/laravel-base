<?php

namespace Creasi\Tests\Fixtures;

use Creasi\Base\Contracts\HasIdentity;
use Creasi\Base\Models\Concerns\WithIdentity;
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
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use WithIdentity;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function password(): Attribute
    {
        return Attribute::set(fn (string $value) => \bcrypt($value));
    }
}
