<?php

namespace Creasi\Base\Enums;

enum EmploymentType: int
{
    use KeyableEnum;

    case Unemployeed = 0;
    case Fulltime = 1;
    case Parttime = 2;
    case Internship = 3;
    case Freelance = 4;

    public function isUnemployeed(): bool
    {
        return $this === self::Unemployeed;
    }

    public function isFulltime(): bool
    {
        return $this === self::Fulltime;
    }

    public function isParttime(): bool
    {
        return $this === self::Parttime;
    }

    public function isInternship(): bool
    {
        return $this === self::Internship;
    }

    public function isFreelance(): bool
    {
        return $this === self::Freelance;
    }
}
