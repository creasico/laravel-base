<?php

namespace Creasi\Base\Database\Models;

use Creasi\Base\Enums\PersonRelativeStatus;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property null|PersonRelativeStatus $status
 */
class PersonRelative extends Pivot
{
    protected $table = 'people_relatives';

    protected $casts = [
        'person_id' => 'int',
        'relative_id' => 'int',
        'status' => PersonRelativeStatus::class,
    ];
}
