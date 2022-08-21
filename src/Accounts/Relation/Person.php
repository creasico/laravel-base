<?php

namespace Creasi\Laravel\Accounts\Relation;

use Creasi\Laravel\Accounts\Type;

enum Person: string
{
    case Owned = 'owned';
    case Employeer = 'employeer';
    case Grandparent = 'grandparent';
    case Parent = 'parent';
    case Spouse = 'spouse';
    case Child = 'child';
    case Sibling = 'sibling';
    case Nephew = 'nephew';
    case Cousin = 'cousin';

    public function related()
    {
        return match($this) {
            self::Owned => Type::Company,
            self::Employeer => Type::Company,
            self::Grandparent => Type::Person,
            self::Parent => Type::Person,
            self::Spouse => Type::Person,
            self::Child => Type::Person,
            self::Sibling => Type::Person,
            self::Nephew => Type::Person,
            self::Cousin => Type::Person,
        };
    }
}
