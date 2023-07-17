<?php

namespace Creasi\Tests\Models;

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
}
