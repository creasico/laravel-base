<?php

namespace Creasi\Tests\Feature\Http;

use Creasi\Base\Database\Models\Business;
use Creasi\Base\Database\Models\Personnel;
use Creasi\Base\Enums\StakeholderType;
use Creasi\Tests\Feature\TestCase;
use Illuminate\Contracts\Routing\UrlRoutable;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;

#[Group('api')]
#[Group('stakeholder')]
abstract class StakeholderTestCase extends TestCase
{
    private array $dataStructure = [
        'id',
        'avatar',
        'name',
        'alias',
        'email',
        'phone',
        'summary',
    ];

    abstract protected function getRelativeType(): StakeholderType;

    final protected function getRoutePath(string|UrlRoutable ...$suffixs): string
    {
        $route = $this->getRelativeType()->key()->plural();

        return parent::getRoutePath((string) $route, ...$suffixs);
    }

    #[Test]
    public function should_receive_404_when_no_data_available(): void
    {
        Sanctum::actingAs($this->user());

        $response = $this->getJson($this->getRoutePath());

        $response->assertNotFound();
    }

    #[Test]
    public function should_able_to_retrieve_all_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $company = Business::factory()->createOne(['name' => 'External Company']);
        $personal = Personnel::factory()->createOne(['name' => 'External Personal']);

        $user->identity->employer->addStakeholder($this->getRelativeType(), $company);
        $user->identity->employer->addStakeholder($this->getRelativeType(), $personal);

        $response = $this->getJson($this->getRoutePath());

        $response->assertOk()->assertJsonStructure([
            'data' => [$this->dataStructure],
            'links' => [],
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_store_new_data(): void
    {
        Sanctum::actingAs($this->user());

        $data = Business::factory()->raw();

        $response = $this->postJson($this->getRoutePath(), $data);

        $response->assertCreated()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_show_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->employer->addStakeholder($this->getRelativeType(), $model);

        $response = $this->getJson($this->getRoutePath($model));

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_update_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->employer->addStakeholder($this->getRelativeType(), $model);

        $response = $this->putJson($this->getRoutePath($model), $model->toArray());

        $response->assertOk()->assertJsonStructure([
            'data' => $this->dataStructure,
            'meta' => [],
        ]);
    }

    #[Test]
    public function should_able_to_delete_existing_data(): void
    {
        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->employer->addStakeholder($this->getRelativeType(), $model);

        $response = $this->deleteJson($this->getRoutePath($model));

        $response->assertNoContent();
    }

    #[Test]
    public function should_able_to_restore_deleted_data(): void
    {
        $this->markTestIncomplete();

        Sanctum::actingAs($user = $this->user());

        $model = Business::factory()->createOne();

        $user->identity->employer->addStakeholder($this->getRelativeType(), $model);

        $model->delete();

        $response = $this->putJson($this->getRoutePath($model, 'restore'));

        $response->assertOk();
    }
}
