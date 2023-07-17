<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('employee')]
class EmployeeTest extends TestCase
{
    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('base/employees');

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());
        $data = Personnel::factory()->raw();

        $response = $this->postJson('base/employees', $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Personnel::factory()->createOne();

        $response = $this->getJson("base/employees/{$model->getRouteKey()}");

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Personnel::factory()->createOne();

        $response = $this->putJson("base/employees/{$model->getRouteKey()}", $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Personnel::factory()->createOne();

        $response = $this->deleteJson("base/employees/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
