<?php

namespace Creasi\Laravel\Accounts;

enum Type: string
{
    case Company = 'company';
    case Person = 'person';

    public function relations()
    {
        return match($this) {
            self::Company => Relation\Company::cases(),
            self::Person => Relation\Company::cases(),
        };
    }
}
