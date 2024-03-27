<?php

namespace Creasi\Tests\Unit\Models;

use Creasi\Base\Database\Models\Personnel;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Workbench\App\Models\User;

#[Group('models')]
#[Group('user')]
class UserTest extends TestCase
{
    #[Test]
    public function it_could_have_profile()
    {
        /** @var User */
        $user = User::factory()->createOne();
        /** @var Personnel */
        $identity = Personnel::factory()->createOne();

        $user->identity()->save($identity);

        $this->assertTrue($identity->user->is($user));
    }
}
