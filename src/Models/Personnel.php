<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\Employee;
use Creasi\Base\Contracts\HasCredential;
use Creasi\Base\Contracts\HasProfile;
use Creasi\Base\Models\Concerns\AsEmployee;
use Creasi\Base\Models\Concerns\WithCredential;
use Creasi\Base\Models\Concerns\WithProfile;
use Creasi\Base\Models\Enums\Gender;
use Creasi\Base\Models\Enums\PersonnelRelativeStatus;

/**
 * @property null|Gender $gender
 * @property-read ?PersonnelRelative $relative
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $relatives
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Database\Factories\PersonnelFactory<static> factory()
 */
class Personnel extends Entity implements Employee, HasCredential, HasProfile
{
    use AsEmployee;
    use WithCredential;
    use WithProfile;

    protected $fillable = [
        'gender',
    ];

    protected $casts = [
        'gender' => Gender::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|static
     */
    public function relatives()
    {
        return $this->belongsToMany(static::class, 'personnel_relatives', 'personnel_id', 'relative_id')
            ->withPivot('status')
            ->using(PersonnelRelative::class)
            ->as('relative');
    }

    public function addRelative(Personnel $relative, PersonnelRelativeStatus $status)
    {
        $this->relatives()->attach($relative, [
            'status' => $status,
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|BusinessRelative
     */
    public function stakeholders()
    {
        return $this->morphToMany(Business::class, 'stakeholder', 'company_relatives', 'company_id', 'stakeholder_id')
            ->withPivot('type')
            ->as('stakeholder');
    }
}
