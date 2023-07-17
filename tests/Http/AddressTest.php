<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Address;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('address')]
class AddressTest extends TestCase
{
    #[Test]
    #[DataProviderExternal(TestCase::class, 'entities')]
    public function should_able_to_retrieve_all_data(string $entity, string $modelClass): void
    {
        Sanctum::actingAs($this->user());
        $model = $modelClass::factory()->withAddress()->create();

        $response = $this->getJson("base/{$entity}/{$model->getKey()}/addresses");

        $response->assertOk();
    }

    #[Test]
    #[DataProviderExternal(TestCase::class, 'entities')]
    public function should_able_to_store_new_data(string $entity, string $modelClass): void
    {
        Sanctum::actingAs($this->user());
        $model = $modelClass::factory()->withAddress()->create();
        $data = Address::factory()->raw();

        $response = $this->postJson("base/{$entity}/{$model->getKey()}/addresses", $data);

        $response->assertCreated();
    }

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
