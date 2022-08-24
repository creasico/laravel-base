<?php

namespace Creasi\Laravel\Accounts;

use Creasi\Laravel\Factories\AccountFactory;
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
 * @property null|Type $type
 * @property null|\Carbon\CarbonImmutable $updated_at
 * @property null|\Carbon\CarbonImmutable $created_at
 * @property null|\Carbon\CarbonImmutable $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Field> $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Field> $setting
 *
 * @method static \Creasi\Laravel\Factories\AccountFactory<Account> factory()
 */
class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'type', 'slug', 'display', 'summary'];

    protected static function newFactory()
    {
        return new AccountFactory();
    }

    public function getCasts()
    {
        return \array_merge(parent::getCasts(), ['type' => Type::class]);
    }

    public function setSlugAttribute(?string $value = null)
    {
        $this->attributes['slug'] = $value ?: Str::slug($this->attributes['name']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Field
     */
    public function fields()
    {
        return $this->morphedByMany(Field::class, 'accountable')
                    ->using(Accountable::class)
                    ->as('defined')
                    ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Field
     */
    public function profile()
    {
        return $this->fields()
                    ->withPivot('payload', 'is_primary')
                    ->where('type', Field\Type::Profile);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Field
     */
    public function settings()
    {
        return $this->fields()
                    ->withPivot('payload')
                    ->where('type', Field\Type::Setting);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|Relation
     */
    public function relations()
    {
        return $this->morphedByMany(Relation::class, 'accountable')
                    ->using(Accountable::class)
                    ->as('defined')
                    ->withTimestamps();
    }
}
