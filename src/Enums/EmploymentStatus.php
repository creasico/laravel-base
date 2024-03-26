<?php

namespace Creasi\Base\Enums;

enum EmploymentStatus: int
{
    use KeyableEnum;

    /**
     * Whether the personnel is not employeed.
     */
    case Unemployeed = 0;

    /**
     * Whether the personnel still active as fulltime employee.
     */
    case Fulltime = 1;

    /**
     * Whether the personnel still active as partime employee.
     */
    case Parttime = 2;

    /**
     * Whether the personnel still being probation.
     */
    case Probation = 3;

    /**
     * Whether the personnel still being probation.
     */
    case Internship = 4;

    /**
     * Whether the personnel is a freelancer.
     */
    case Freelance = 5;

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

    public function isProbation(): bool
    {
        return $this === self::Probation;
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
