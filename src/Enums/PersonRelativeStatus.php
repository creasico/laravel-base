<?php

namespace Creasi\Base\Enums;

enum PersonRelativeStatus: int
{
    use KeyableEnum;

    /**
     * Whether the personnel is registered as a children of the employee.
     */
    case Child = 1;

    /**
     * Whether the personnel is registered as a spouse of the employee.
     */
    case Spouse = 2;

    /**
     * Whether the personnel is registered as a sibling of the employee.
     */
    case Sibling = 3;

    /**
     * Whether the personnel is registered as a siblingchild of the employee.
     */
    case SiblingsChild = 4;

    /**
     * Whether the personnel is registered as a parent of the employee.
     */
    case Parent = 5;

    /**
     * Whether the personnel is registered as a parent's sibling of the employee.
     */
    case ParentsSibling = 6;

    /**
     * Whether the personnel is registered as a grandparent of the employee.
     */
    case Grandparent = 7;

    /**
     * Whether the personnel is registered as a grandchildren of the employee.
     */
    case Grandchild = 8;

    /**
     * Whether the personnel is registered as a cousin of the employee.
     */
    case Cousin = 9;

    public function isChild(): bool
    {
        return $this === self::Child;
    }

    public function isSpouse(): bool
    {
        return $this === self::Spouse;
    }

    public function isSibling(): bool
    {
        return $this === self::Sibling;
    }

    public function isSiblingsChild(): bool
    {
        return $this === self::SiblingsChild;
    }

    public function isParent(): bool
    {
        return $this === self::Parent;
    }

    public function isParentsSibling(): bool
    {
        return $this === self::ParentsSibling;
    }

    public function isGrandparent(): bool
    {
        return $this === self::Grandparent;
    }

    public function isGrandchild(): bool
    {
        return $this === self::Grandchild;
    }

    public function isCousin(): bool
    {
        return $this === self::Cousin;
    }
}
