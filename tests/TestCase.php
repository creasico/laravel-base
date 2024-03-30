<?php

namespace Creasi\Tests;

use Closure;
use Creasi\Base\Database\Models\Person;
use Creasi\Base\Enums\PersonnelStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Models\User;

abstract class TestCase extends Orchestra
{
    use DatabaseMigrations;
    use WithWorkbench;

    private ?User $currentUser = null;

    final protected function user(
        array|Closure $attrs = [],
        ?PersonnelStatus $status = null,
        bool $isPrimary = true
    ): User {
        if (! $this->currentUser?->exists) {
            $this->currentUser = User::factory()
                ->withProfile(Person::factory()->asEmployee($status, $isPrimary))
                ->createOne($attrs);
        }

        return $this->currentUser;
    }
}
