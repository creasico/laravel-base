<?php

declare(strict_types=1);

namespace Creasi\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
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

    /**
     * @define-env disableRoute
     */
    #[Test]
    #[Group('routes')]
    public function should_able_to_disable_routes()
    {
        /** @var \Countable */
        $routes = Route::getRoutes();

        $this->assertCount(1, $routes);
    }
}
