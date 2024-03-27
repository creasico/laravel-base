<?php

namespace Creasi\Base\Database\Models\Concerns;

use Carbon\CarbonInterface;
use Creasi\Base\Database\Models\BusinessRelative;
use Creasi\Base\Database\Models\Contracts\Company;
use Creasi\Base\Database\Models\Entity;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Enums\EmploymentStatus;
use Creasi\Base\Enums\StakeholderType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $owners
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $subsidiaries
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $customers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $suppliers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $vendors
 *
 * @method HasMany|BusinessRelative owners()
 * @method HasMany|BusinessRelative subsidiaries()
 * @method HasMany|BusinessRelative customers()
 * @method HasMany|BusinessRelative suppliers()
 * @method HasMany|BusinessRelative vendors()
 *
 * @mixin Company
 */
trait AsCompany
{
    /**
     * Initialize the trait.
     */
    final protected static function bootAsCompany(): void
    {
        foreach (StakeholderType::cases() as $stakeholder) {
            static::resolveRelationUsing(
                (string) $stakeholder->key()->plural(),
                fn (Company $model) => $model->stakeholders()->where([
                    'type' => $stakeholder,
                ])
            );
        }
    }

    /**
     * Initialize the trait.
     */
    final protected function initializeAsCompany(): void
    {
        // .
    }

    /**
     * {@inheritdoc}
     */
    protected function relatives(string $relative, bool $forward = true): MorphToMany
    {
        $relation = $forward
            ? $this->morphedByMany($relative, 'stakeholder', 'business_relatives', 'business_id')
            : $this->morphToMany(static::class, 'stakeholder', 'business_relatives', null, 'business_id');

        return $relation->using(BusinessRelative::class)
            ->withPivot('type', 'code', 'status', 'start_date', 'finish_date');
    }

    /**
     * {@inheritdoc}
     */
    public function companyRelatives(): MorphToMany
    {
        return $this->relatives(static::class)
            ->as('stakeholder');
    }

    /**
     * {@inheritdoc}
     */
    public function individualRelatives(): MorphToMany
    {
        return $this->relatives(Personnel::class)
            ->as('stakeholder');
    }

    /**
     * {@inheritdoc}
     */
    public function employees(): MorphToMany
    {
        return $this->relatives(Personnel::class)
            ->withPivot('is_primary', 'employment_status')
            ->wherePivot('type', '=', StakeholderType::Employee)
            ->as('employment');
    }

    public function addEmployee(
        Personnel $employee,
        EmploymentStatus $status,
        CarbonInterface $startDate,
        bool $isPrimary = false
    ) {
        return $this->employees()->attach($employee, [
            'is_primary' => $isPrimary,
            'type' => StakeholderType::Employee,
            'employment_status' => $status,
            'start_date' => $startDate,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function stakeholders(): HasMany
    {
        return $this->hasMany(BusinessRelative::class);
    }

    /**
     * {@inheritdoc}
     */
    public function addStakeholder(StakeholderType $type, Entity $stakeholder): static
    {
        $this->relatives($stakeholder::class)->attach($stakeholder, [
            'type' => $type,
        ]);

        return $this->fresh();
    }
}
