<?php

namespace Creasi\Base\Enums;

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
        return \in_array($this, [self::Owner, self::Subsidiary], true);
    }

    public function isPartner(): bool
    {
        return \in_array($this, [self::Supplier, self::Vendor], true);
    }

    public function isOwner(): bool
    {
        return $this === self::Owner;
    }

    public function isSubsidiary(): bool
    {
        return $this === self::Subsidiary;
    }

    public function isCustomer(): bool
    {
        return $this === self::Customer;
    }

    public function isSupplier(): bool
    {
        return $this === self::Supplier;
    }

    public function isVendor(): bool
    {
        return $this === self::Vendor;
    }
}
