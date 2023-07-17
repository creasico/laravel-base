<?php

namespace Creasi\Tests\Http\Account;

use Creasi\Base\Models\User;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('account')]
#[Group('account.settings')]
class SettingTest extends TestCase
{
    #[Test]
    public function should_be_exists(): void
    {
        Sanctum::actingAs(User::factory()->createOne());

        $response = $this->getJson('base/account/settings');

        $response->assertOk();
    }
}
