<?php

namespace Creasi\Laravel\Accounts\Relation;

use Creasi\Laravel\Accounts\Type;

enum Company: string
{
    case Owner = 'owner';
    case Employee = 'employee';
    case Partner = 'partner';

    public function related()
    {
        return match($this) {
            self::Owner => Type::Person,
            self::Employee => Type::Person,
            self::Partner => Type::Company,
        };
    }
}
