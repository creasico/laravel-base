<?php

namespace Workbench\Database\Factories;

use Creasi\Base\Database\Factories\Concerns\WithProfile;
use Orchestra\Testbench\Factories\UserFactory as Factory;
use Workbench\App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    use WithProfile;

    public function modelName()
    {
        return User::class;
    }
}
