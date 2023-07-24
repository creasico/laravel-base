<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Contracts\Company;
use Creasi\Base\Models\BusinessRelative;
use Creasi\Base\Models\Employment;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Creasi\Base\Models\Personnel;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $owners
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $subsidiaries
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $customers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $suppliers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, BusinessRelative> $vendors
 *
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative owners()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative subsidiaries()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative customers()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative suppliers()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative vendors()
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
        foreach (BusinessRelativeType::cases() as $stakeholder) {
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Personnel
     */
    public function employees()
    {
        return $this->belongsToMany(Personnel::class, 'employments', 'employer_id', 'employee_id')
            ->withPivot('is_primary', 'type', 'status', 'start_date', 'finish_date')
            ->using(Employment::class)
            ->as('employment');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany|BusinessRelative
     */
    protected function relatives(string $relative, bool $forward = true)
    {
        $relation = $forward
            ? $this->morphedByMany($relative, 'stakeholder', 'business_relatives', 'business_id')
            : $this->morphToMany(static::class, 'stakeholder', 'business_relatives', null, 'business_id');

        return $relation->using(BusinessRelative::class)->withPivot('type', 'is_internal', 'code');
    }

    public function companyRelatives()
    {
        return $this->relatives(static::class)->as('stakeholder');
    }

    public function individualRelatives()
    {
        return $this->relatives(Personnel::class)->as('stakeholder');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|BusinessRelative
     */
    public function stakeholders()
    {
        return $this->hasMany(BusinessRelative::class);
    }

    public function addStakeholder(
        BusinessRelativeType $type,
        Entity $stakeholder,
        bool $internal = null,
    ): static {
        $this->relatives(\get_class($stakeholder))->attach($stakeholder, [
            'type' => $type,
            'is_internal' => $internal ?? $type->isInternal(),
        ]);

        return $this->fresh();
    }
}
