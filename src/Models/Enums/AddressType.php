<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

/**
 * Only binary-type genders are supported here.
 */
enum AddressType: int
{
    use KeyableEnum;

    /**
     * Determine whether the address is actualy their residential address.
     */
    case Resident = 0;

    /**
     * Determine whether the address is as described in their legal document.
     */
    case Legal = 1;

    public function isResident(): bool
    {
        return $this->value === self::Resident->value;
    }
}
