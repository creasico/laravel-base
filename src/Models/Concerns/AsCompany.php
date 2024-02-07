<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\BusinessRelative;
use Creasi\Base\Models\Contracts\Company;
use Creasi\Base\Models\Employment;
use Creasi\Base\Models\Entity;
use Creasi\Base\Models\Enums\BusinessRelativeType;
use Creasi\Base\Models\Personnel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * {@inheritdoc}
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Personnel::class, 'employments', 'employer_id', 'employee_id')
            ->withPivot('is_primary', 'type', 'status', 'start_date', 'finish_date')
            ->using(Employment::class)
            ->as('employment');
    }

    /**
     * {@inheritdoc}
     */
    protected function relatives(string $relative, bool $forward = true): MorphToMany
    {
        $relation = $forward
            ? $this->morphedByMany($relative, 'stakeholder', 'business_relatives', 'business_id')
            : $this->morphToMany(static::class, 'stakeholder', 'business_relatives', null, 'business_id');

        return $relation->using(BusinessRelative::class)->withPivot('type', 'code');
    }

    /**
     * {@inheritdoc}
     */
    public function companyRelatives(): MorphToMany
    {
        return $this->relatives(static::class)->as('stakeholder');
    }

    /**
     * {@inheritdoc}
     */
    public function individualRelatives(): MorphToMany
    {
        return $this->relatives(Personnel::class)->as('stakeholder');
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
    public function addStakeholder(BusinessRelativeType $type, Entity $stakeholder): static
    {
        $this->relatives(\get_class($stakeholder))->attach($stakeholder, [
            'type' => $type,
        ]);

        return $this->fresh();
    }
}
