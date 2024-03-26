<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Models\Concerns\AsEmployee;
use Creasi\Base\Database\Models\Concerns\WithCredential;
use Creasi\Base\Database\Models\Concerns\WithProfile;
use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Database\Models\Contracts\HasCredential;
use Creasi\Base\Database\Models\Contracts\HasProfile;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\PersonnelRelativeStatus;

/**
 * @property-read ?PersonnelRelative $relative
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $relatives
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Creasi\Base\Database\Factories\PersonnelFactory<Personnel> factory()
 */
class Personnel extends Entity implements Employee, HasCredential, HasProfile
{
    use AsEmployee;
    use WithCredential;
    use WithProfile;

    protected $fillable = ['gender'];

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
