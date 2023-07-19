<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Address;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('address')]
class AddressTest extends TestCase
{
    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Address::factory()->createOne();

        $response = $this->getJson("base/addresses/{$model->getRouteKey()}");

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Address::factory()->createOne();

        $response = $this->putJson("base/addresses/{$model->getRouteKey()}", $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Address::factory()->createOne();

        $response = $this->deleteJson("base/addresses/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
