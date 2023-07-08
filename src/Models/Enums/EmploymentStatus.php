<?php

namespace Creasi\Base\Models\Enums;

enum EmploymentStatus: int
{
    use KeyableEnum;

    case Permanent = 0;
    case Contract = 1;
    case Probation = 2;
}
