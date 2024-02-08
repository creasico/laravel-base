<?php

namespace Workbench\Database\Factories;

use Creasi\Base\Models\Factories\Concerns\WithIdentity;
use Orchestra\Testbench\Factories\UserFactory as Factory;
use Workbench\App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    use WithIdentity;

    public function modelName()
    {
        return User::class;
    }
}
