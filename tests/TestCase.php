<?php

namespace Creasi\Tests;

use Creasi\Laravel\Facades\Account;
use Creasi\Laravel\AccountServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            AccountServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'creasi.account' => Account::class,
        ];
    }
}
