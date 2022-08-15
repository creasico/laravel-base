<?php

namespace Creasi\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Account extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'creasi.account';
    }
}
