<?php

namespace Creasi\Laravel\Models\Account;

enum Type: string
{
    case Company = 'company';
    case Person = 'person';

    public function connections()
    {
        return match ($this) {
            self::Company => Connection\Company::cases(),
            self::Person => Connection\Company::cases(),
        };
    }
}
