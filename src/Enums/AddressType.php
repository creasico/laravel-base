<?php

namespace Creasi\Base\Enums;

enum AddressType: int
{
    use KeyableEnum;

    /**
     * Whether the address is actual residential address.
     */
    case Resident = 0;

    /**
     * Whether the address is actual legal document.
     */
    case Legal = 1;

    public function isResident(): bool
    {
        return $this === self::Resident;
    }

    public function isLegal(): bool
    {
        return $this === self::Legal;
    }
}
