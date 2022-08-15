<?php

namespace Creasi\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Accounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'creasi.accounts';
    }
}
