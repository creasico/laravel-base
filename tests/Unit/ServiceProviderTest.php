<?php

declare(strict_types=1);

namespace Creasi\Tests\Unit;

use Creasi\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\Attributes\DefineEnvironment;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('serviceProvider')]
class ServiceProviderTest extends TestCase
{
    use WithFaker;

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function disableRoute($app)
    {
        $app->config->set('creasi.base.routes_enable', false);
    }

    #[Test]
    #[Group('routes')]
    #[DefineEnvironment('disableRoute')]
    public function should_able_to_disable_routes()
    {
        $prefix = config('creasi.base.routes_prefix');

        $this->assertFalse(Route::getRoutes()->hasNamedRoute("$prefix.*"));
    }
}
