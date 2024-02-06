<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

enum EmploymentStatus: int
{
    use KeyableEnum;

    case Permanent = 0;
    case Contract = 1;
    case Probation = 2;
}
