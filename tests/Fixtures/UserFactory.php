<?php

namespace Creasi\Tests\Fixtures;

use Creasi\Base\Models\Factories\Concerns\WithIdentity;
use Orchestra\Testbench\Factories\UserFactory as Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    use WithIdentity;

    public function modelName()
    {
        return app('creasi.base.user_model');
    }
}
