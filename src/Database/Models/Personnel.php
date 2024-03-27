<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Database\Models\Concerns\AsEmployee;
use Creasi\Base\Database\Models\Concerns\WithCredential;
use Creasi\Base\Database\Models\Contracts\Employee;
use Creasi\Base\Database\Models\Contracts\HasCredential;
use Creasi\Base\Enums\Gender;
use Creasi\Base\Enums\PersonnelRelativeStatus;
use Creasi\Nusa\Models\Concerns\WithRegency;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property null|string $nik
 * @property null|string $prefix
 * @property null|string $suffix
 * @property null|\Carbon\CarbonImmutable $birth_date
 * @property null|int $birth_place_code
 * @property null|string $summary
 * @property-read null|Regency $birthPlace
 * @property-read ?PersonnelRelative $relative
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Personnel> $relatives
 * @property-read BusinessRelative $stakeholder
 *
 * @method static \Creasi\Base\Database\Factories\PersonnelFactory<Personnel> factory()
 */
class Personnel extends Entity implements Employee, HasCredential
{
    use AsEmployee;
    use WithCredential;
    use WithRegency {
        regency as birthPlace;
    }

    protected $fillable = [
        // 'nik',
        'prefix',
        'suffix',
        'birth_date',
        'birth_place_code',
        'gender',
    ];

    protected $casts = [
        'birth_date' => 'immutable_date',
        'birth_place_code' => 'int',
        'gender' => Gender::class,
    ];

    protected $regencyKey = 'birth_place_code';

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

    public function parents(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Parent);
    }

    public function children(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Child);
    }

    public function spouse(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Spouse)
            ->where('gender', '=', $this->gender->inverted());
    }

    public function siblings(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Sibling);
    }

    public function siblingsChildren(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::SiblingsChild);
    }

    public function parentsSiblings(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::ParentsSibling);
    }

    public function grandParents(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Grandparent);
    }

    public function grandChildren(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Grandchild);
    }

    public function cousins(): BelongsToMany
    {
        return $this->relatives()
            ->wherePivot('status', PersonnelRelativeStatus::Cousin);
    }
}
