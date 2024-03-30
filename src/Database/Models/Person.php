<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Models\Concerns\AsPersonnel;
use Creasi\Base\Database\Models\Contracts\HasCredential;
use Creasi\Base\Database\Models\Contracts\Personnel;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\PersonRelativeStatus;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Nusa\Models\Concerns\WithRegency;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property null|string $nik
 * @property null|string $prefix
 * @property null|string $suffix
 * @property null|\Carbon\CarbonImmutable $birth_date
 * @property null|int $birth_place_code
 * @property null|string $summary
 * @property-read null|Regency $birthPlace
 * @property-read ?PersonRelative $relative
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Person> $relatives
 * @property-read OrganizationRelative $stakeholder
 *
 * @method static \Creasi\Base\Database\Factories\PersonFactory<Person> factory()
 */
class Person extends Entity implements HasCredential, Personnel
{
    use AsPersonnel;
    use WithRegency {
        regency as birthPlace;
    }

    protected $fillable = [
        'user_id',
        'prefix',
        'suffix',
        'birth_date',
        'birth_place_code',
        'gender',
    ];

    protected $casts = [
        'user_id' => 'int',
        'birth_date' => 'immutable_date',
        'birth_place_code' => 'int',
        'gender' => Gender::class,
    ];

    protected $regencyKey = 'birth_place_code';

    /**
     * {@inheritdoc}
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('creasi.base.user_model'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|static
     */
    public function relatives()
    {
        return $this->belongsToMany(static::class, 'people_relatives', 'person_id', 'relative_id')
            ->withPivot('status')
            ->using(PersonRelative::class)
            ->as('relative');
    }

    public function addRelative(Person $relative, PersonRelativeStatus $status)
    {
        $this->relatives()->attach($relative, [
            'status' => $status,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function employer(): Attribute
    {
        $this->loadMissing('primaryEmployer');

        return Attribute::get(fn () => $this->primaryEmployer?->first());
    }

    /**
     * {@inheritdoc}
     */
    public function businessRelatives()
    {
        return $this->morphToMany(Organization::class, 'stakeholder', 'organizations_relatives', 'organization_id', 'stakeholder_id')
            ->using(OrganizationRelative::class)
            ->withPivot('type', 'code', 'status', 'start_date', 'finish_date');
    }

    /**
     * {@inheritdoc}
     */
    public function companies(): MorphToMany
    {
        return $this->businessRelatives()
            ->wherePivot('type', '=', StakeholderType::Owner)
            ->as('ownership');
    }

    /**
     * {@inheritdoc}
     */
    public function employers(): MorphToMany
    {
        return $this->businessRelatives()
            ->wherePivot('type', '=', StakeholderType::Employee)
            ->withPivot('is_primary', 'personnel_status')
            ->as('employment');
    }

    /**
     * {@inheritdoc}
     */
    public function primaryEmployer(): MorphToMany
    {
        return $this->employers()
            ->wherePivot('is_primary', '=', true)
            ->latest();
    }
}
