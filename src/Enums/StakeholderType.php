<?php

namespace Creasi\Base\Enums;

/**
 * Typically a stakeholder that has some interests in the company.
 */
enum StakeholderType: int
{
    use KeyableEnum;

    /**
     * A company or individual stackholder who own the business.
     */
    case Owner = 1;

    /**
     * A company that act as subsidiary or child business.
     */
    case Subsidiary = 2;

    /**
     * A individual that act as employee of the business.
     */
    case Employee = 3;

    /**
     * A company or individual that act as purchase goods from the business.
     */
    case Customer = 4;

    /**
     * A company or individual who provides raw materials for the business
     * so they can produce their goods.
     */
    case Supplier = 5;

    /**
     * A company or individual who provides pre-made or even ready-made goods
     * for the business so they can proceed the raw materials into goods.
     */
    case Vendor = 6;

    public static function internals(): array
    {
        return [self::Owner, self::Subsidiary, self::Employee];
    }

    public static function externals(): array
    {
        return [self::Customer, self::Supplier, self::Vendor];
    }

    public function isInternal(): bool
    {
        return \in_array($this, self::internals(), true);
    }

    public function isExternal(): bool
    {
        return \in_array($this, self::externals(), true);
    }

    public function isOwner(): bool
    {
        return $this === self::Owner;
    }

    public function isSubsidiary(): bool
    {
        return $this === self::Subsidiary;
    }

    public function isEmployee(): bool
    {
        return $this === self::Employee;
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
