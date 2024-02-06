<?php

namespace Creasi\Base\Models\Enums;

use Creasi\Base\Support\Enums\KeyableEnum;

/**
 * Typically a stakeholder that has some interests in the company.
 */
enum BusinessRelativeType: int
{
    use KeyableEnum;

    /**
     * For individual stackholder who own the company.
     */
    case Owner = 1;

    /**
     * For company that act as subsidiary or child company.
     */
    case Subsidiary = 2;

    /**
     * For company or individual that act as business customers.
     */
    case Customer = 3;

    /**
     * For company or individual that act as business suppliers.
     */
    case Supplier = 4;

    /**
     * For company or individual that act as business vendors.
     */
    case Vendor = 5;

    /**
     * Whether it's an internal or external stakeholder.
     */
    public function isInternal(): bool
    {
        return \in_array($this->value, [
            self::Owner->value,
            self::Subsidiary->value,
        ], true);
    }
}
