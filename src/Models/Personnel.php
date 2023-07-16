<?php

namespace Creasi\Base\Models;

use Creasi\Base\Contracts\HasFileUploads;
use Creasi\Base\Contracts\Contactable;
use Creasi\Base\Models\Concerns\HasAvatar;
use Creasi\Base\Models\Concerns\WithFileUploads;
use Creasi\Base\Models\Concerns\HasIdentity;
use Creasi\Base\Models\Enums\PersonnelRelativeStatus;
use Creasi\Nusa\Contracts\HasAddresses;
use Creasi\Nusa\Models\Concerns\WithAddresses;

/**
 * @property ?string $photo_path
 * @property-read ?PersonnelRelative $relative
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $relatives
 * @property-read CompanyRelative $stakeholder
 *
 * @method static \Database\Factories\PersonnelFactory<static> factory()
 */
class Personnel extends Model implements HasAddresses, Contactable, HasFileUploads
{
    use HasAvatar;
    use HasIdentity;
    use WithAddresses;
    use WithFileUploads;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone_number',
        'summary',
    ];

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

    public function addRelative(Personnel $relative, PersonnelRelativeStatus $status, ?string $remark = null)
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
