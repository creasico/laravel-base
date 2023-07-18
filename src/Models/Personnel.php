<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\Employee;
use Creasi\Base\Models\Concerns\WithIdentity;
use Creasi\Base\Models\Enums\PersonnelRelativeStatus;

/**
 * @property ?string $photo_path
 * @property-read ?PersonnelRelative $relative
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $relatives
 * @property-read CompanyRelative $stakeholder
 *
 * @method static \Database\Factories\PersonnelFactory<static> factory()
 */
class Personnel extends Entity implements Employee
{
    use WithIdentity;

    protected $casts = [
        // .
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|static
     */
    public function relatives()
    {
        return $this->belongsToMany(static::class, 'personnel_relatives', 'personnel_id', 'relative_id')
            ->withPivot('status', 'remark')
            ->using(PersonnelRelative::class)
            ->as('relative');
    }

    public function addRelative(Personnel $relative, PersonnelRelativeStatus $status, string $remark = null)
    {
        $this->relatives()->attach($relative, [
            'status' => $status,
            'remark' => $remark,
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|CompanyRelative
     */
    public function stakeholders()
    {
        return $this->morphToMany(Company::class, 'stakeholder', 'company_relatives', 'company_id', 'stakeholder_id')
            ->withPivot('type', 'remark')
            ->as('stakeholder');
    }
}
