<?php

namespace Creasi\Tests;

use Creasi\Laravel\Accounts\ServiceProvider;
use Creasi\Laravel\Facades\Accounts;
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
            'creasi.accounts' => Accounts::class,
        ];
    }
}
