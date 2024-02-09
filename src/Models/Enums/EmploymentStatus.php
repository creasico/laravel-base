<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

enum EmploymentStatus: int
{
    use KeyableEnum;

    case Candidate = 0;
    case Probation = 1;
    case Contract = 2;
    case Permanent = 3;

    public function isCandidate(): bool
    {
        return $this === self::Candidate;
    }

    public function isProbation(): bool
    {
        return $this === self::Probation;
    }

    public function isContract(): bool
    {
        return $this === self::Contract;
    }

    public function isPermanent(): bool
    {
        return $this === self::Permanent;
    }
}
