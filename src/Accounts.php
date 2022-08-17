<?php

namespace Creasi\Laravel;

use Illuminate\Support\Facades\Facade;

class Accounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Accounts\Repository::class;
    }
}
