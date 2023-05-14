<?php

namespace Creasi\Laravel\Models;

use Creasi\Laravel\Factories\AccountFactory;
use Creasi\Laravel\Models\Accountable;
use Creasi\Laravel\Models\Account\Field;
use Creasi\Laravel\Models\Account\Field\Type;
use Creasi\Laravel\Models\Account\Connection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property string $name
 * @property null|string $slug
 * @property null|string $display
 * @property null|string $summary
 * @property null|\Carbon\CarbonImmutable $updated_at
 * @property null|\Carbon\CarbonImmutable $created_at
 * @property null|\Carbon\CarbonImmutable $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Field> $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Field> $setting
 *
 * @method static AccountFactory<Account> factory(callable|array|int|null  $count, callable|array  $state)
 */
class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'display', 'summary'];

    protected static function newFactory()
    {
        return new AccountFactory();
    }

    public function slug(): Attribute
    {
        return new Attribute(
            set: fn (?string $value, array $attributes) => $value ?: Str::slug($attributes['name'])
        );
    }

    public function display(): Attribute
    {
        return new Attribute(
            get: fn (?string $value, array $attributes) => $value ?: $attributes['name']
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Account
     */
    public function connections()
    {
        return $this->belongsToMany(static::class, 'account_connections', 'account_id', 'connected_id')
                    ->using(Connection::class)
                    ->as('connection');
    }
}
