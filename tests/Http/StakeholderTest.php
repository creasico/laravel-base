<?php

namespace Creasi\Tests\Http;

use Creasi\Base\Models\Enums\CompanyRelativeType;
use Creasi\Base\Models\Personnel;
use Creasi\Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('stakeholder')]
class StakeholderTest extends TestCase
{
    public static function stakeholders()
    {
        $stakeholders = [];

        foreach (CompanyRelativeType::cases() as $stakeholder) {
            if ($stakeholder->isInternal()) {
                continue;
            }

            $stakeholders[(string) $stakeholder->key()] = [$stakeholder];
        }

        return $stakeholders;
    }

    #[Test]
    #[DataProvider('stakeholders')]
    public function should_able_to_retrieve_all_data(CompanyRelativeType $stakeholder): void
    {
        Sanctum::actingAs($this->user());

        $route = $stakeholder->key()->plural();
        $response = $this->getJson("base/{$route}");

        $response->assertOk();
    }

    #[Test]
    #[DataProvider('stakeholders')]
    public function should_able_to_store_new_data(CompanyRelativeType $stakeholder): void
    {
        Sanctum::actingAs($this->user());
        $data = Personnel::factory()->raw();
        $route = $stakeholder->key()->plural();

        $response = $this->postJson("base/{$route}", $data);

        $response->assertCreated();
    }

    #[Test]
    #[DataProvider('stakeholders')]
    public function should_able_to_show_existing_data(CompanyRelativeType $stakeholder): void
    {
        Sanctum::actingAs($this->user());
        $model = Personnel::factory()->createOne();
        $route = $stakeholder->key()->plural();

        $response = $this->getJson("base/{$route}/{$model->getRouteKey()}");

        $response->assertOk();
    }

    #[Test]
    #[DataProvider('stakeholders')]
    public function should_able_to_update_existing_data(CompanyRelativeType $stakeholder): void
    {
        Sanctum::actingAs($this->user());
        $model = Personnel::factory()->createOne();
        $route = $stakeholder->key()->plural();

        $response = $this->putJson("base/{$route}/{$model->getRouteKey()}", $model->toArray());

        $response->assertOk();
    }

    #[Test]
    #[DataProvider('stakeholders')]
    public function should_able_to_delete_existing_data(CompanyRelativeType $stakeholder): void
    {
        Sanctum::actingAs($this->user());
        $model = Personnel::factory()->createOne();
        $route = $stakeholder->key()->plural();

        $response = $this->deleteJson("base/{$route}/{$model->getRouteKey()}");

        $response->assertNoContent();
    }
}
