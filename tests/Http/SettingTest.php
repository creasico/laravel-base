<?php

namespace Creasi\Tests\Http;

use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('account')]
#[Group('setting')]
class SettingTest extends TestCase
{
    #[Test]
    public function should_able_to_retrieve_setting_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('base/setting');

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_setting_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->putJson('base/setting');

        $response->assertOk();
    }
}
