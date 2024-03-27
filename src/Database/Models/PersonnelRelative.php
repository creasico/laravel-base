<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Enums\PersonnelRelativeStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property null|PersonnelRelativeStatus $status
 */
class PersonnelRelative extends Pivot
{
    protected $casts = [
        'personnel_id' => 'int',
        'relative_id' => 'int',
        'status' => PersonnelRelativeStatus::class,
    ];
}
