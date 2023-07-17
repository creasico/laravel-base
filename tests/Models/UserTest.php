<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Identity;
use Creasi\Base\Models\User;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('user')]
class UserTest extends TestCase
{
    #[Test]
    public function should_be_exists(): void
    {
        $model = User::factory()->createOne();

        $this->assertModelExists($model);
    }

    #[Test]
    public function it_could_have_profile()
    {
        $user = User::factory()->createOne();
        $identity = Identity::factory()->createOne();

        $user->profile()->save($identity);

        $this->assertTrue($identity->user->is($user));
    }
}
