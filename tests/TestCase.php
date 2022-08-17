<?php

namespace Creasi\Tests;

use Creasi\Laravel\Accounts\ServiceProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
