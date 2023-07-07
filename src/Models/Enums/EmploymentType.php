<?php

namespace Creasi\Base\Models\Enums;

enum EmploymentType: int
{
    use KeyableEnum;

    case Unemployeed = 0;
    case Fulltime = 1;
    case Parttime = 2;
    case Internship = 3;
    case Freelance = 4;
}
