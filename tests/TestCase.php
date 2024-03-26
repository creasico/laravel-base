<?php

namespace Creasi\Tests;

use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Models\User;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;
    use WithWorkbench;

    private ?User $currentUser = null;

    final protected function user(array|Closure $attrs = []): User
    {
        if (! $this->currentUser?->exists) {
            $this->currentUser = User::factory()
                ->withIdentity(fn ($p) => $p->withProfile()->withCompany(true))
                ->createOne($attrs);
        }

        return $this->currentUser;
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app->useEnvironmentPath(\dirname(__DIR__));
    }
}
