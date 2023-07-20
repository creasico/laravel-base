<?php

namespace Creasi\Base\Models\Concerns;

use Creasi\Base\Models\Employment;
use Creasi\Base\Models\Personnel;

/**
 * @mixin \Creasi\Base\Contracts\HasFileUploads
 */
trait AsEmployer
{
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
}
