<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Company;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('company')]
class CompanyTest extends TestCase
{
    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson('base/companies');

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());
        $data = Company::factory()->raw();

        $response = $this->postJson('base/companies', $data);

        $response->assertCreated();
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Company::factory()->createOne();

        $response = $this->getJson("base/companies/{$model->getRouteKey()}");

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Company::factory()->createOne();

        $response = $this->putJson("base/companies/{$model->getRouteKey()}", $model->toArray());

        $response->assertOk();
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($this->user());
        $model = Company::factory()->createOne();

        $response = $this->deleteJson("base/companies/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
