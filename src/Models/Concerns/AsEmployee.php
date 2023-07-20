<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Business;
use Creasi\Base\Models\Employment;

/**
 * @mixin \Creasi\Base\Contracts\HasFileUploads
 */
trait AsEmployee
{
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
}
