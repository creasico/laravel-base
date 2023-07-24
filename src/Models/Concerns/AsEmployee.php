<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Business;
use Creasi\Base\Models\Employment;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

        $this->makeHidden('primaryEmployer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Business
     */
    public function employers()
    {
        return $this->belongsToMany(Business::class, 'employments', 'employee_id', 'employer_id')
            ->withPivot('is_primary', 'type', 'status', 'start_date', 'finish_date')
            ->using(Employment::class)
            ->as('employment');
    }

    public function company(): Attribute
    {
        $this->loadMissing('primaryEmployer');

        return Attribute::get(fn () => $this->primaryEmployer?->first());
    }

    public function primaryEmployer()
    {
        return $this->employers()->wherePivot('is_primary', '=', true);
    }
}
