<?php

namespace Creasi\Base\Database\Models\Concerns;

use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\Employment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin \Creasi\Base\Contracts\Employee
 */
trait AsEmployee
{
    /**
     * Initialize the trait.
     */
    final protected function initializeAsEmployee(): void
    {
        $this->append('company');

        $this->makeHidden('primaryCompany');
    }

    /**
     * {@inheritdoc}
     */
    public function employers(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'employments', 'employee_id', 'employer_id')
            ->withPivot('is_primary', 'type', 'status', 'start_date', 'finish_date')
            ->using(Employment::class)
            ->as('employment');
    }

    /**
     * {@inheritdoc}
     */
    public function company(): Attribute
    {
        $this->loadMissing('primaryCompany');

        return Attribute::get(fn () => $this->primaryCompany?->first());
    }

    /**
     * {@inheritdoc}
     */
    public function primaryCompany(): BelongsToMany
    {
        return $this->employers()->wherePivot('is_primary', '=', true);
    }
}
