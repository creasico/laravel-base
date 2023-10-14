<?php

namespace Creasi\Tests\Models;

use Creasi\Base\Models\Personnel;
use Creasi\Tests\Fixtures\User;
use Creasi\Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('models')]
#[Group('user')]
class UserTest extends TestCase
{
    #[Test]
    public function it_could_have_profile()
    {
        $user = User::factory()->createOne();
        $identity = Personnel::factory()->createOne();

        $user->identity()->save($identity);

        $this->assertTrue($identity->user->is($user));
    }
}
