<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Tests\Feature\TestCase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('account')]
#[Group('setting')]
class SettingTest extends TestCase
{
    protected string $apiPath = 'setting';

    #[Test]
    public function should_able_to_retrieve_setting_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_setting_data(): void
    {
        $this->withoutMiddleware(ThrottleRequests::class);

        Sanctum::actingAs($this->user());

        $response = $this->putJson($this->getRoutePath());

        $response->assertOk();
    }
}
