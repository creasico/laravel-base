<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

enum EmploymentStatus: int
{
    use KeyableEnum;

    case Probation = 0;
    case Contract = 1;
    case Permanent = 2;

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
