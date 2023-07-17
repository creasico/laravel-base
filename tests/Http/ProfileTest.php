<?php

namespace Creasi\Tests\Http;

use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('account')]
#[Group('profile')]
class ProfileTest extends TestCase
{
    #[Test]
    public function should_able_to_retrieve_profile_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('base/profile');

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_profile_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->putJson('base/profile');

        $response->assertOk();
    }
}
