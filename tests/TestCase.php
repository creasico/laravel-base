<?php

namespace Creasi\Tests;

use Creasi\Laravel\Facades\Account;
use Creasi\Laravel\Account\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'creasi.account' => Account::class,
        ];
    }
}
